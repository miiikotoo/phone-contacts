'use client'

import React from 'react';

import { useRouter } from 'next/navigation';
import { useParams } from 'next/navigation';
import Link from 'next/link';

import { useSingleContact } from '../../_hooks/useContact';

interface ContactData {
  id: number;
  name: string;
  kana: string;
  number: string[];
  mail: string[];
}

export const Edit = () => {
  const { id } = useParams();
  const router = useRouter();

  const { fetchContact } = useSingleContact(id as string);
  const [contactData, setContactData] = React.useState<ContactData | null>(null);
  React.useEffect(() => {
    const fetchData = async () => {
      try {
        const data = await fetchContact();
        setContactData(data);
      } catch (error) {
        console.error('Error fetching contact:', error);
      }
    };
    fetchData();
  }, [fetchContact]);
  if (!contactData) {
    return (
      <div className="grid grid-rows-[20px_1fr_20px] items-center justify-items-center min-h-screen p-8 pb-20 gap-16 sm:p-20 font-[family-name:var(--font-geist-sans)]">
        <main className="flex flex-col gap-[32px] row-start-2 items-center sm:items-start">
          <h1 className="text-3xl font-bold">Loading...</h1>
        </main>
      </div>
    );
  }
  const { name, kana, number, mail } = contactData;
  console.log(contactData);
  console.log(name, kana, number, mail);
  console.log(id);

  const handleSubmit = async (event: React.FormEvent<HTMLFormElement>) => {
    event.preventDefault();
    const formData = new FormData(event.currentTarget);
    const data = Object.fromEntries(formData.entries());

    console.log(data);

    try {
      const response = await fetch(`/api/contacts/${id}`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(data),
      });

      if (response.ok) {
        router.push(`/phone-contact/${id}/view`);
      } else {
        console.error('Error updating contact');
      }
    } catch (error) {
      console.error('Error:', error);
    }
  };
  return (
    <div className="grid grid-rows-[20px_1fr_20px] items-center justify-items-center min-h-screen p-8 pb-20 gap-16 sm:p-20 font-[family-name:var(--font-geist-sans)]">
      <main className="flex flex-col gap-[32px] row-start-2 items-center sm:items-start">
        <h1 className="text-3xl font-bold">Edit Contact</h1>
        <p className="text-sm/6 text-center sm:text-left font-[family-name:var(--font-geist-mono)]">
          This is a simple phone contact app.
        </p>
        <form className="flex flex-col gap-4" onSubmit={handleSubmit}>
          <input
            type="text"
            name='name'
            placeholder="名前"
            className="border border-gray-300 rounded p-2"
            defaultValue={name}
          />
          <input
            type="text"
            name='kana'
            placeholder="フリガナ"
            className="border border-gray-300 rounded p-2"
            defaultValue={kana}
          />
          <input
            type="tel"
            name='number'
            placeholder="電話番号"
            className="border border-gray-300 rounded p-2"
            defaultValue={number}
          />
          <button id='add-number' type='button'>+</button>

          <input
            type="email"
            name='mail'
            placeholder="アドレス"
            className="border border-gray-300 rounded p-2"
            defaultValue={mail}
          />
          <button id='add-mail' type='button'>+</button>

          <button type="submit" className="bg-blue-500 text-white rounded p-2">
            Update Contact
          </button>
        </form>
        <Link href={`/phone-contact/${id}/view`} className="text-sm/6 text-center sm:text-left font-[family-name:var(--font-geist-mono)]">
          Back to Contact
        </Link>
      </main>
    </div>
  );
}
export default Edit;