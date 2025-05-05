'use client'

import React, { useState, useEffect } from 'react';

import { useParams } from 'next/navigation';
import { useRouter } from 'next/navigation';
import Link from 'next/link';

import { useSingleContact, useDeleteContact } from '../../_hooks/useContact';

import Button from '@/app/_components/commons/Button';

interface UserData {
  id: number;
  name: string;
  email: string;
  phone: string;
}

export const View = () => {
  const [userData, setUserData] = useState<UserData | null>(null);
  const router = useRouter();

  const { id } = useParams();
  
  const { fetchContact, error, isLoading } = useSingleContact(id as string);

  useEffect(() => {
    const fetchData = async () => {
      try {
        const data = await fetchContact();
        setUserData(data);
      } catch (error) {
        console.error("Error fetching contact:", error);
      }
    };

    fetchData();
  }, [fetchContact]);

  // 削除
  const { deleteContact, error: deleteError, isLoading: isDeleting } = useDeleteContact(id as string);
  // 削除ボタンを押したときの処理
  const handleDeleteClick = () => {
    if (confirm("Are you sure you want to delete this contact?")) {
      deleteContact();
      if (deleteError) {
        console.error("Error deleting contact:", deleteError);
      }
      if (isDeleting) {
        console.log("Deleting contact...");
      }
      // 削除後は/phone-contactにリダイレクト
      router.push("/phone-contact");
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
          ) : (
            <div className="text-sm/6 text-center sm:text-left font-[family-name:var(--font-geist-mono)]">
              <h2 className="text-lg font-bold">{userData?.name}</h2>
              <p>{userData?.email}</p>
              <p>{userData?.phone}</p>
            </div>
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
        
        <Link href="/phone-contact" className="text-sm/6 text-center sm:text-left font-[family-name:var(--font-geist-mono)]">
          Back to Contacts
        </Link>
      </main>
    </div>
  )
}

export default View;