'use client'

import React, { useState, useEffect } from 'react';

import { useParams } from 'next/navigation';
import { useRouter } from 'next/navigation';
import Link from 'next/link';

import { useSingleContact, useDeleteContact } from '../../_hooks/useContact';

import Button from '@/app/_components/commons/Button';

// type import
import { Contact, Phone, Mail } from '@/types/phone-contacts';

export const View = () => {
  const [userData, setUserData] = useState<Contact | null>(null);
  const [phones, setPhones] = useState<Phone[]>([]);
  const [mails, setMails] = useState<Mail[]>([]);

  const router = useRouter();

  const { id } = useParams();
  
  const { fetchContact, error, isLoading } = useSingleContact(id as string);

  useEffect(() => {
    const fetchData = async () => {
      try {
        const data = await fetchContact();

        setUserData(data);
        if (data) {
          setPhones(data.phones);
          setMails(data.mails);
        }

      } catch (error) {
        console.error("Error fetching contact:", error);
      }
    };

    fetchData();
  }, [fetchContact]);

  // 削除
  const { deleteContact } = useDeleteContact(id as string);
  
  // 削除ボタンを押したときの処理
  const handleDeleteClick = async () => {
    if (confirm("Are you sure you want to delete this contact?")) {
      const { response } = await deleteContact();
      if (response) {
        if (response.status !== 'error') {
          console.log('Contact deleted successfully:', response);
          router.push(`/phone-contact`);
        } else {
          console.error('Error deleting contact:', response);
          alert(response.message || 'エラーが発生しました。もう一度お試しください。');
        }
      } else {
        console.error('Error deleting contact: No response');
        alert('削除に失敗しました。もう一度お試しください。');
      }
    }
  };
  
  return (
    <div className="grid grid-rows-[20px_1fr_20px] items-center justify-items-center min-h-screen p-8 pb-20 gap-16 sm:p-20 font-[family-name:var(--font-geist-sans)]">
      <main className="flex flex-col gap-[32px] row-start-2 items-center sm:items-start">
        <h1 className="text-3xl font-bold">View Contact</h1>
        <p className="text-sm/6 text-center sm:text-left font-[family-name:var(--font-geist-mono)]">
          This is a simple phone contact app.
        </p>
        {
          isLoading ? (
            <div className="text-sm/6 text-center sm:text-left font-[family-name:var(--font-geist-mono)]">
              Loading...
            </div>
          ) : error ? (
            <div className="text-sm/6 text-center sm:text-left font-[family-name:var(--font-geist-mono)]">
              Error: {error.message}
            </div>
          ) : !userData ? (
            <div className="text-sm/6 text-center sm:text-left font-[family-name:var(--font-geist-mono)]">
              No data found
            </div>
          ) : (
            <div className="text-sm/6 text-center sm:text-left font-[family-name:var(--font-geist-mono)]">
              <h2 className="text-lg font-bold">{userData?.name}</h2>
              <h2 className="text-lg font-bold">{userData?.kana}</h2>

              <h2 className="text-lg font-bold">Phones</h2>
              {
                phones.length === 0 ? (
                  <p className="text-sm/6 text-center sm:text-left font-[family-name:var(--font-geist-mono)]">No phones found</p>
                ) : (
                  phones.map((phone: Phone) => (
                    <div key={phone.id} className="flex flex-col gap-2">
                      <p className="text-sm/6 text-center sm:text-left font-[family-name:var(--font-geist-mono)]">{phone.number}</p>
                    </div>
                  ))
                )
              }

              <h2 className="text-lg font-bold">Mails</h2>
              {
                mails.length === 0 ? (
                  <p className="text-sm/6 text-center sm:text-left font-[family-name:var(--font-geist-mono)]">No mails found</p>
                ) : (
                  mails.map((mail: Mail) => (
                    <div key={mail.id} className="flex flex-col gap-2">
                      <p className="text-sm/6 text-center sm:text-left font-[family-name:var(--font-geist-mono)]">{mail.mail}</p>
                    </div>
                  ))
                )
              }
              <Link href={`/phone-contact/${id}/edit`} className="text-sm/6 text-center sm:text-left font-[family-name:var(--font-geist-mono)]">
                Edit Contact
              </Link>
              <Button
                variant="primary"
                size="medium"
                onClick={() => {
                  handleDeleteClick();
                }}
              >
                DELETE
              </Button>
            </div>
          )
        }
        
        <Link href="/phone-contact" className="text-sm/6 text-center sm:text-left font-[family-name:var(--font-geist-mono)]">
          Back to Contacts
        </Link>
      </main>
    </div>
  )
}

export default View;