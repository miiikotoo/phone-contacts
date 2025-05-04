'use client'

import React, { useState, useEffect } from 'react';

import { useParams } from 'next/navigation';

import { useSingleContact } from '../../_hooks/useContact';

import Link from 'next/link';

interface UserData {
  id: number;
  name: string;
  email: string;
  phone: string;
}

export const View = () => {
  const [userData, setUserData] = useState<UserData | null>(null);

  const { id } = useParams();

  const { fetchContact } = useSingleContact(id as string);
  
  useEffect(() => {
    const fetchData = async () => {
      try {
        const data = await fetchContact();
        setUserData(data);
      } catch (error) {
        console.error('Error fetching contact:', error);
      }
    };

    fetchData();
  }, [fetchContact]);
  
  return (
    <div className="grid grid-rows-[20px_1fr_20px] items-center justify-items-center min-h-screen p-8 pb-20 gap-16 sm:p-20 font-[family-name:var(--font-geist-sans)]">
      <main className="flex flex-col gap-[32px] row-start-2 items-center sm:items-start">
        <h1 className="text-3xl font-bold">View Contact</h1>
        <p className="text-sm/6 text-center sm:text-left font-[family-name:var(--font-geist-mono)]">
          This is a simple phone contact app.
        </p>
        {
          userData ? (
            <div className="text-sm/6 text-center sm:text-left font-[family-name:var(--font-geist-mono)]">
              <h2 className="text-lg font-bold">{userData.name}</h2>
              <p>{userData.email}</p>
              <p>{userData.phone}</p>
              <Link href={`/phone-contact/${userData.id}/edit`} className="text-blue-500 hover:underline">
                Edit
              </Link>
            </div>
          ) : (
            <div className="text-sm/6 text-center sm:text-left font-[family-name:var(--font-geist-mono)]">
              Loading...
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