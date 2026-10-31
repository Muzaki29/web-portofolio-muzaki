"""Read-only deployment input checks; never target the production app path."""
import io
import os
from pathlib import Path
import shutil
import subprocess
import tarfile
import tempfile
import unittest


ROOT = Path(__file__).resolve().parents[1]
BASH = shutil.which("bash") or "C:/Program Files/Git/bin/bash.exe"


def bash_path(path):
    value = str(path).replace("\\", "/")
    return "/" + value[0].lower() + value[2:] if os.name == "nt" else value


class DeploymentInputTest(unittest.TestCase):
    @unittest.skipIf(os.name == "nt", "Unix ownership/mode check runs on the Linux deployment host")
    def test_code_restore_preserves_live_database_and_directory_mode(self):
        with tempfile.TemporaryDirectory(prefix="muzaki-archive-test-") as directory:
            folder = Path(directory)
            live = folder / "app"
            database = live / "database"
            database.mkdir(parents=True)
            database.chmod(0o770)
            sqlite = database / "database.sqlite"
            sqlite.write_text("original fixture", encoding="utf-8")
            (live / "index.php").write_text("original code", encoding="utf-8")
            backup = folder / "backup.tar.gz"
            subprocess.run(["tar", "--exclude=./database/*.sqlite*", "-czf", str(backup), "-C", str(live), "."], check=True)
            with tarfile.open(backup) as archive:
                self.assertFalse(any("database.sqlite" in member.name for member in archive))
            source = folder / "source.tar"
            with tarfile.open(source, "w") as archive:
                member = tarfile.TarInfo("database")
                member.type, member.mode = tarfile.DIRTYPE, 0o755
                archive.addfile(member)
            before = database.stat()
            subprocess.run(["tar", "--no-overwrite-dir", "-xf", str(source), "-C", str(live)], check=True)
            sqlite.write_text("new session fixture", encoding="utf-8")
            subprocess.run(["tar", "--no-overwrite-dir", "-xzf", str(backup), "-C", str(live)], check=True)
            after = database.stat()
            self.assertEqual((after.st_uid, after.st_gid, after.st_mode), (before.st_uid, before.st_gid, before.st_mode))
            self.assertEqual(sqlite.read_text(encoding="utf-8"), "new session fixture")

    def test_rejects_invalid_revision_and_mutable_data_before_server_access(self):
        with tempfile.TemporaryDirectory(prefix="muzaki-deploy-test-") as directory:
            folder = Path(directory)
            script = folder / "deploy.sh"
            source = (ROOT / "scripts/deploy-production.sh").read_text(encoding="utf-8")
            # A missing fixture app also prevents server access if a guard regresses.
            source = source.replace("app=/var/www/web-portofolio-muzaki", "app=/missing-muzaki-test-app")
            script.write_text(source, encoding="utf-8")
            subprocess.run([BASH, "-n", bash_path(script)], check=True)
            for filename in (".env", "database/database.sqlite", "database/database.sqlite-wal"):
                with self.subTest(filename=filename):
                    archive = folder / "unsafe.tar"
                    with tarfile.open(archive, "w") as output:
                        output.addfile(tarfile.TarInfo(filename), io.BytesIO())
                    result = self.run_script(script, archive, "a" * 40)
                    self.assertEqual(result.returncode, 2, result.stderr)
                    self.assertIn("Unsafe release archive path", result.stderr)
            result = self.run_script(script, folder / "missing.tar", "invalid")
            self.assertEqual(result.returncode, 2, result.stderr)
            self.assertIn("Invalid release revision", result.stderr)

    def run_script(self, script, archive, revision):
        return subprocess.run(
            [BASH, "-c", 'export PATH=/usr/bin:/bin:$PATH; bash "$1" "$2" "$3"',
             "--", bash_path(script), bash_path(archive), revision],
            capture_output=True, text=True, timeout=10,
        )


if __name__ == "__main__":
    unittest.main()
