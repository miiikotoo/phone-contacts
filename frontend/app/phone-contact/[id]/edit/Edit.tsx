'use client'

import React, { useState, useEffect } from 'react';

import { useRouter } from 'next/navigation';
import { useParams } from 'next/navigation';
import Link from 'next/link';

import { useSingleContact, useUpdateContact } from '../../_hooks/useContact';

import { Contact, Phone, Mail } from '@/types/phone-contacts';

export const Edit = () => {
  const { id } = useParams();
  const router = useRouter();

  const [userData, setUserData] = useState<Contact | null>(null);
  const [phones, setPhones] = useState<Phone[]>([]);
  const [mails, setMails] = useState<Mail[]>([]);
  
  const { updateContact} = useUpdateContact(id as string);
  const handleSubmit = async (event: React.FormEvent<HTMLFormElement>) => {
    event.preventDefault();
    const formData = new FormData(event.currentTarget);
    const data = Object.fromEntries(formData.entries());

    // フォームデータを整形
    // phoneとmailのデータを配列に変換
    const phonesData = phones.map((phone) => ({
      id: phone.id,
      number: data[phone.id] || phone.number,
    }));
    const mailsData = mails.map((mail) => ({
      id: mail.id,
      mail: data[mail.id] || mail.mail,
    }));
    // フォームデータを整形
    const contactData = {
      name: data.name,
      kana: data.kana,
      phones: phonesData,
      mails: mailsData,
    };
    // console.log('contactData:', contactData);
    // return;

    // useUpdateContactを使用してデータを送信
    const { response } = await updateContact(contactData);
    if (response) {
      if (response.status !== 'error') {
        console.log('Contact updated successfully:', response);
        router.push(`/phone-contact/${id}/view`);
      } else {
        console.error('Error updating contact:', response);
        console.error('Error message:', response.errors);
        alert(response.message || 'エラーが発生しました。もう一度お試しください。');
      }
    } else {
      console.error('Error updating contact: No response');
      alert('更新に失敗しました。もう一度お試しください。');
    }
  };

  const { fetchContact, error, isLoading } = useSingleContact(id as string);

  useEffect(() => {
    const fetchData = async () => {
      const data = await fetchContact();
      setUserData(data);

      if (data) {
        setPhones(data.phones);
        setMails(data.mails);
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
          userData && (
            <form className="flex flex-col gap-4" onSubmit={handleSubmit}>
              <input
                type="text"
                name='name'
                placeholder="名前"
                className="border border-gray-300 rounded p-2"
                defaultValue={userData.name}
              />
              <input
                type="text"
                name='kana'
                placeholder="フリガナ"
                className="border border-gray-300 rounded p-2"
                defaultValue={userData.kana}
              />

              <div id="phone-numbers">
              {
                phones.length === 0 ? (
                  <div className="flex items-center gap-2">
                    <input
                      type="tel"
                      name={`phone`}
                      placeholder="電話番号"
                      className="border border-gray-300 rounded p-2"
                    />
                    <button id='delete-phone' type='button'>-</button>
                  </div>
                ) : (
                  phones.map((phone: Phone, index: number) => (
                    <div key={index} className="flex items-center gap-2">
                      <input
                        type="tel"
                        name={phone.id}
                        placeholder="電話番号"
                        className="border border-gray-300 rounded p-2"
                        defaultValue={phone.number}
                        id={phone.id}
                      />
                      <button id='delete-phone' type='button'>-</button>
                    </div>
                  ))
                )
              }
              </div>
              <button id='add-number' type='button'>+</button>
              <div id="emails">
              {
                mails.length === 0 ? (
                  <div className="flex items-center gap-2">
                    <input
                      type="email"
                      name={`mail`}
                      placeholder="アドレス"
                      className="border border-gray-300 rounded p-2"
                    />
                    <button id='delete-mail' type='button'>-</button>
                  </div>
                ) : (
                  mails.map((mail: Mail, index: number) => (
                    <div key={index} className="flex items-center gap-2">
                      <input
                        type="email"
                        name={mail.id}
                        id={mail.id}
                        placeholder="アドレス"
                        className="border border-gray-300 rounded p-2"
                        defaultValue={mail.mail}
                      />
                      <button id='delete-mail' type='button'>-</button>
                    </div>
                  ))
                )
              }
              </div>
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