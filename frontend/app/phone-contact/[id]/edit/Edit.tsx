'use client'

import React, { useState, useEffect } from 'react';

import { useRouter } from 'next/navigation';
import { useParams } from 'next/navigation';
import Link from 'next/link';

import { useSingleContact, useUpdateContact } from '../../_hooks/useContact';

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
  
  const { updateContact, error: updateError, isLoading: isUpdating } = useUpdateContact(id as string);
  const handleSubmit = async (event: React.FormEvent<HTMLFormElement>) => {
    event.preventDefault();
    const formData = new FormData(event.currentTarget);
    const data = Object.fromEntries(formData.entries());
    console.log(data);

    // useUpdateContactを使用してデータを送信
    await updateContact(data);
    if (updateError) {
      console.error('Error updating contact:', updateError);
    }
    if (isUpdating) {
      console.log('Updating contact...');
    }
    if (data) {
      console.log('Contact updated:', data);
      // 成功時は`/phone-contact`にリダイレクト
      router.push(`/phone-contact/${id}/view`);
    }
  };

  const { fetchContact, error, isLoading } = useSingleContact(id as string);
  const [contactData, setContactData] = useState<ContactData | null>(null);

  useEffect(() => {
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

  return (
    <div className="grid grid-rows-[20px_1fr_20px] items-center justify-items-center min-h-screen p-8 pb-20 gap-16 sm:p-20 font-[family-name:var(--font-geist-sans)]">
      <main className="flex flex-col gap-[32px] row-start-2 items-center sm:items-start">
        <h1 className="text-3xl font-bold">Edit Contact</h1>
        <p className="text-sm/6 text-center sm:text-left font-[family-name:var(--font-geist-mono)]">
          This is a simple phone contact app.
        </p>
        {
          isUpdating ? (
            <div className="text-sm/6 text-center sm:text-left font-[family-name:var(--font-geist-mono)]">
              Updating...
            </div>
          ) : updateError ? (
            <div className="text-sm/6 text-center sm:text-left font-[family-name:var(--font-geist-mono)]">
              Error: {updateError.message}
            </div>
          ) : null
        }
        {
          contactData && (
            <form className="flex flex-col gap-4" onSubmit={handleSubmit}>
              <input
                type="text"
                name='name'
                placeholder="名前"
                className="border border-gray-300 rounded p-2"
                defaultValue={contactData.name}
              />
              <input
                type="text"
                name='kana'
                placeholder="フリガナ"
                className="border border-gray-300 rounded p-2"
                defaultValue={contactData.kana}
              />
              <input
                type="tel"
                name='number'
                placeholder="電話番号"
                className="border border-gray-300 rounded p-2"
                defaultValue={contactData.number}
              />
              <button id='add-number' type='button'>+</button>

              <input
                type="email"
                name='mail'
                placeholder="アドレス"
                className="border border-gray-300 rounded p-2"
                defaultValue={contactData.mail}
              />
              <button id='add-mail' type='button'>+</button>

              <button type="submit" className="bg-blue-500 text-white rounded p-2">
                Update Contact
              </button>
            </form>
          )
        }
        <Link href={`/phone-contact/${id}/view`} className="text-sm/6 text-center sm:text-left font-[family-name:var(--font-geist-mono)]">
          Back to Contact
        </Link>
      </main>
    </div>
  );
}
export default Edit;