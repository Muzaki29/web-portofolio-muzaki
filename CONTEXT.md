# web-portofolio-muzaki

Personal portfolio of Muzaki Abdullah Irsyad: one public landing page plus case studies, with an admin CMS where the owner edits the content shown there.

## Language

**Landing page**:
The public page at `/` that shows the profile, works, experience, timeline, certificates and the contact form.
_Avoid_: home, portfolio page

**Project**:
One work the owner has made, shown in the "Karya" / "Works" section of the Landing page; each has one Case study.
_Avoid_: work item, works

**Case study**:
The detail page of one Project, at `/work/{slug}`.
_Avoid_: project page, work detail

**Content item**:
One Experience, Timeline or Certificate entry shown on the Landing page.
_Avoid_: entry, record

**Setting**:
One Profile & Contact value (such as location or contact email) the owner edits in the admin; when unset, the Landing page shows a default.
_Avoid_: config, option
