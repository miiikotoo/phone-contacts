'use client'

import React from 'react';
import Link from 'next/link';
import { useRouter } from 'next/navigation';

export const Create = () => {
  const router = useRouter();

  // formのsubmitを受け取る
  const handleSubmit = async (e: React.FormEvent<HTMLFormElement>) => {
    
    e.preventDefault();
    const formData = new FormData(e.currentTarget);
    const data = Object.fromEntries(formData.entries());
    console.log(data);

    // バックエンドapiにPOSTリクエストを送信
    // todo. corsエラーでlocalhost(backend api)での実行ができない
    const res = await fetch("https://jsonplaceholder.typicode.com/users", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(data),
    });

    if (!res.ok) {
      throw new Error("Failed to create contact");
    }
    // 成功時は`/phone-contact`にリダイレクト
    router.push("/phone-contact");
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
          <input type="text"          
            name='kana'
            placeholder="フリガナ"
            className="border border-gray-300 rounded p-2"
           />
          <input
            type="tel"
            name='number'
            placeholder="電話番号"
            className="border border-gray-300 rounded p-2"
          />
          <button id='add-number' type='button'>+</button>
          <input
            type="email"
            name='mail'
            placeholder="アドレス"
            className="border border-gray-300 rounded p-2"
          />
          <button id='add-mail' type='button'>+</button>
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