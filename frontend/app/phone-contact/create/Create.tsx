'use client'

import React, { useState } from 'react';
import Link from 'next/link';
import { useRouter } from 'next/navigation';

import { useCreateContact } from '../_hooks/useContact';

export const Create = () => {
  const router = useRouter();
  const { createContact } = useCreateContact();
  const [phoneNumbers, setPhoneNumbers] = useState(['']);
  const [mailAddresses, setMailAddresses] = useState(['']); // メールアドレスの状態を追加

  // 電話番号の処理
  const handleAddPhoneNumber = () => {
    setPhoneNumbers([...phoneNumbers, '']);
  };

  const handleRemovePhoneNumber = (indexToRemove: number) => {
    if (phoneNumbers.length <= 1) return;
    const updatedNumbers = phoneNumbers.filter((_, index) => index !== indexToRemove);
    setPhoneNumbers(updatedNumbers);
  };

  const handlePhoneNumberChange = (index: number, value: string) => {
    const updatedNumbers = [...phoneNumbers];
    updatedNumbers[index] = value;
    setPhoneNumbers(updatedNumbers);
  };

  // メールアドレスの処理
  const handleAddMail = () => {
    setMailAddresses([...mailAddresses, '']);
  };

  const handleRemoveMail = (indexToRemove: number) => {
    if (mailAddresses.length <= 1) return;
    const updatedMails = mailAddresses.filter((_, index) => index !== indexToRemove);
    setMailAddresses(updatedMails);
  };

  const handleMailChange = (index: number, value: string) => {
    const updatedMails = [...mailAddresses];
    updatedMails[index] = value;
    setMailAddresses(updatedMails);
  };

  const handleSubmit = async (e: React.FormEvent<HTMLFormElement>) => {
    e.preventDefault();

    const originalFormData = new FormData(e.currentTarget);
    
    // 送信データの作成
    const submitData: { [key: string]: string } = {};
    submitData['name'] = originalFormData.get('name') as string;
    submitData['kana'] = originalFormData.get('kana') as string;

    // 電話番号を追加
    phoneNumbers
      .filter(num => num.trim() !== '')
      .forEach((number, index) => {
        submitData[`phone[${index}]`] = number;
      });

    // メールアドレスを追加
    mailAddresses
      .filter(mail => mail.trim() !== '')
      .forEach((mail, index) => {
        submitData[`mail[${index}]`] = mail;
      });

    const { response } = await createContact(submitData);

    if (response) {
      console.log('Response:', response);

      if (response.status === 'error') {
        console.log('Error creating contact:', response.message);
        return;
      }

      router.push('/phone-contact');
    } else {
      console.error('Error creating contact');
    }
  }

  return (
    <div className="grid grid-rows-[20px_1fr_20px] items-center justify-items-center min-h-screen p-8 pb-20 gap-16 sm:p-20 font-[family-name:var(--font-geist-sans)]">
      <main className="flex flex-col gap-[32px] row-start-2 items-center sm:items-start">
        <h1 className="text-3xl font-bold">Create Contact</h1>
        <p className="text-sm/6 text-center sm:text-left font-[family-name:var(--font-geist-mono)]">
          This is a simple phone contact app.
        </p>
        <form className="flex flex-col gap-4" onSubmit={handleSubmit}>
          <input
            type="text"
            name='name'
            placeholder="名前"
            className="border border-gray-300 rounded p-2"
          />
          <input
            type="text"          
            name='kana'
            placeholder="フリガナ"
            className="border border-gray-300 rounded p-2"
          />
          {/* 電話番号入力フィールド */}
          {phoneNumbers.map((number, index) => (
            <div key={index} className="flex gap-2 items-center">
              <input
                type="tel"
                value={number}
                onChange={(e) => handlePhoneNumberChange(index, e.target.value)}
                placeholder="電話番号"
                className="flex-1 border border-gray-300 rounded p-2"
              />
              {phoneNumbers.length > 1 && (
                <button
                  type="button"
                  onClick={() => handleRemovePhoneNumber(index)}
                  className="bg-red-100 text-red-600 rounded p-2 hover:bg-red-200 transition duration-200"
                >
                  削除
                </button>
              )}
            </div>
          ))}
          <button
            id='add-number'
            type='button'
            onClick={handleAddPhoneNumber}
            className="bg-gray-200 text-gray-700 rounded p-2 hover:bg-gray-300 transition duration-200"
          >
            電話番号を追加
          </button>

          {/* メールアドレス入力フィールド */}
          {mailAddresses.map((mail, index) => (
            <div key={index} className="flex gap-2 items-center">
              <input
                type="email"
                value={mail}
                onChange={(e) => handleMailChange(index, e.target.value)}
                placeholder="メールアドレス"
                className="flex-1 border border-gray-300 rounded p-2"
              />
              {mailAddresses.length > 1 && (
                <button
                  type="button"
                  onClick={() => handleRemoveMail(index)}
                  className="bg-red-100 text-red-600 rounded p-2 hover:bg-red-200 transition duration-200"
                >
                  削除
                </button>
              )}
            </div>
          ))}
          <button
            id='add-mail'
            type='button'
            onClick={handleAddMail}
            className="bg-gray-200 text-gray-700 rounded p-2 hover:bg-gray-300 transition duration-200"
          >
            メールアドレスを追加
          </button>

          <button
            type="submit"
            className="bg-blue-500 text-white rounded p-2 hover:bg-blue-600 transition duration-200"
          >
            Create Contact
          </button>
        </form>
        <Link href="/phone-contact" className="text-sm/6 text-center sm:text-left font-[family-name:var(--font-geist-mono)]">
          Back to Contacts
        </Link>
      </main>
    </div>
  );
}

export default Create;