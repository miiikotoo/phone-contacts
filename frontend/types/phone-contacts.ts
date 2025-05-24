// contact type
// @typedef {Object} Contact
// @property {int} id - The unique identifier for the contact.
// @property {string} name - The name of the contact.
// @property {string} kana - The kana of the contact.
// @property {mail} mails - The email address of the contact.
// @property {phone} phones - The phone number of the contact.
// @property {datetime} modified - The modified of the contact.
// @property {datetime} created - The created of the contact.

export interface Contact {
  id: string;
  name: string;
  kana: string;
  phones: Phone[];
  mails: Mail[];
  modified: string;
  created: string;
}

// phones tupe
export type Phone = {
  id: string;
  contact_id: number;
  number: string;
  created: string;
  modified: string;
}

// mails type
export type Mail = {
  id: string;
  contact_id: number;
  mail: string;
  created: string;
  modified: string;
}