'use client'
import React from 'react'
import Link from 'next/link'

import { useGetContacts } from './_hooks/useContact'

export default function Index() {

  const { data, error, isLoading } = useGetContacts();
  
  return (
    <div className="grid grid-rows-[20px_1fr_20px] items-center justify-items-center min-h-screen p-8 pb-20 gap-16 sm:p-20 font-[family-name:var(--font-geist-sans)]">
      <main className="flex flex-col gap-[32px] row-start-2 items-center sm:items-start">
        <h1 className="text-3xl font-bold">Phone Contact</h1>
        <Link href="/phone-contact/create" className="text-sm/6 text-center sm:text-left font-[family-name:var(--font-geist-mono)]">
          Create Contact
        </Link>
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
            <div className="flex flex-col gap-6 text-sm/6 text-center sm:text-left font-[family-name:var(--font-geist-mono)]">
              {data.contacts.map((contact: any) => (
                <div key={contact.id} className="flex flex-col gap-2">
                  <p className="text-lg font-bold">{contact.name}</p>
                  <Link href={`/phone-contact/${contact.id}/view`} className="text-blue-500 hover:underline">
                    View
                  </Link>
                </div>
              ))}
            </div>
          )
        }
      </main>
    </div>
  )
}