export type Locale = 'id' | 'en'
export type Translated<T = string> = Record<Locale, T>

export interface SharedProps {
  auth: { user: { id: number; name: string; email: string } | null }
  flash: { success?: string | null }
  [key: string]: unknown
}

export interface Tag {
  icon: string
  label: string
}

export interface FieldSchema {
  name: string
  label: string
  type: 'text' | 'textarea' | 'url' | 'email' | 'images'
  translatable?: boolean
  required?: boolean
  hint?: string
  group?: string
}

export interface SectionSchema {
  label: string
  singular: string
  title_field: string
  subtitle_field: string
  fields: FieldSchema[]
}

/** A CMS field value: plain string, or {id, en} for translatable fields. */
export type FieldValue = string | Translated | null
export type FieldValues = Record<string, FieldValue>
