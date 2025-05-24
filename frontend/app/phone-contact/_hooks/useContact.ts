// バックエンドapiからcontactsにリクエストするためのcustom hook
import { useState } from "react";

import useSWR from "swr";
import { fetcher } from '@/app/_lib/fetcher';

export const useGetContacts = () => {
  const url = "http://localhost:61221/api/contacts"

  const { data, error, isLoading } = useSWR(url, fetcher);
  return { data, error, isLoading };
}

/**
 * 
 * @param id 
 * @returns { DataTransfer, error: Error, isLoading: boolean }
 */
export const useSingleContact = (id: string) => {
  const url = `http://localhost:61221/api/contacts/${id}`

  const { data, error, isLoading } = useSWR(url, fetcher);

  const fetchContact = async () => {
    return data;
  }

  return { fetchContact, error, isLoading };
}

/**
 * createContact
 * @param contactData
 * @returns { createContact: () => Promise<void>, data: Data, error: Error, isLoading: boolean }
 */
export const useCreateContact = () => {
  const url = "http://localhost:61221/api/contacts/create"
  
  // const [isLoading, setIsLoading] = useState(false);
  // const [error, setError] = useState<Error | null>(null);
  // const [data, setData] = useState<any>(null);

  const createContact = async (contactData: any) => {
    const response = await fetcher(url, 'POST', contactData);
      
    return { response };
  }
  return { createContact };
}

/**
 * updateContact
 * @param id
 * @param contactData
 * @returns { updateContact: () => Promise<void>, data: Data, error: Error, isLoading: boolean }
 */
export const useUpdateContact = (id: string) => {
  const url = `http://localhost:61221/api/contacts/${id}/update`

  const updateContact = async (contactData: any) => {
    const response = await fetcher(url, 'PUT', contactData);
    return { response };
  }
  return { updateContact };
}

/**
 * deleteContact
 * @param id
 * @returns { deleteContact: () => Promise<void>, error: Error, isLoading: boolean }
 */
export const useDeleteContact = (id: string) => {
  const url = `http://localhost:61221/api/contacts/${id}/delete`;

  const deleteContact = async () => {
    const response = await fetcher(url, 'DELETE');
    return { response };
  }
  return { deleteContact };
}