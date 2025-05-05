// バックエンドapiからcontactsにリクエストするためのcustom hook
import { useState } from "react";

import useSWR from "swr";
import { fetcher } from '@/app/_lib/fetcher';

export const useGetContacts = () => {
  const url = "https://jsonplaceholder.typicode.com/users";
  // todo. corsエラーでlocalhost(backend api)での実行ができない
  // const url = "http://localhost:62221/contacts"

  const { data, error, isLoading } = useSWR(url, fetcher);
  return { data, error, isLoading };
}

/**
 * 
 * @param id 
 * @returns { DataTransfer, error: Error, isLoading: boolean }
 */
export const useSingleContact = (id: string) => {
  const url = `https://jsonplaceholder.typicode.com/users/${id}`;
  // todo. corsエラーでlocalhost(backend api)での実行ができない
  // const url = `http://localhost:62221/contacts/${id}`

  const { data, error, isLoading } = useSWR(url, fetcher);

  const fetchContact = async () => {
    if (!data) {
      throw new Error("No data found");
    }
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
  const url = "https://jsonplaceholder.typicode.com/users";
  // todo. corsエラーでlocalhost(backend api)での実行ができない
  // const url = "http://localhost:62221/contacts"
  
  const [isLoading, setIsLoading] = useState(false);
  const [error, setError] = useState<Error | null>(null);
  const [data, setData] = useState<any>(null);

  const createContact = async (contactData: any) => {
    setIsLoading(true);
    try {
      const response = await fetcher(url, 'POST', contactData);
      setData(response);
    } catch (error) {
      setError(error as Error);
    } finally {
      setIsLoading(false);
    }
  }
  return { createContact, data, error, isLoading };
}

/**
 * updateContact
 * @param id
 * @param contactData
 * @returns { updateContact: () => Promise<void>, data: Data, error: Error, isLoading: boolean }
 */
export const useUpdateContact = (id: string) => {
  const url = `https://jsonplaceholder.typicode.com/users/${id}`;
  // todo. corsエラーでlocalhost(backend api)での実行ができない
  // const url = `http://localhost:62221/contacts/${id}`

  const [isLoading, setIsLoading] = useState(false);
  const [error, setError] = useState<Error | null>(null);
  const [data, setData] = useState<any>(null);

  const updateContact = async (contactData: any) => {
    setIsLoading(true);
    try {
      const response = await fetcher(url, 'PUT', contactData);
      setData(response);
    } catch (error) {
      setError(error as Error);
    } finally {
      setIsLoading(false);
    }
  }
  return { updateContact, data, error, isLoading };
}

/**
 * deleteContact
 * @param id
 * @returns { deleteContact: () => Promise<void>, error: Error, isLoading: boolean }
 */
export const useDeleteContact = (id: string) => {
  const url = `https://jsonplaceholder.typicode.com/users/${id}`;
  // todo. corsエラーでlocalhost(backend api)での実行ができない
  // const url = `http://localhost:62221/contacts/${id}`

  const [isLoading, setIsLoading] = useState(false);
  const [error, setError] = useState<Error | null>(null);
  const [data, setData] = useState<any>(null);
  const deleteContact = async () => {
    setIsLoading(true);
    try {
      const response = await fetcher(url, 'DELETE');
      if (!response.ok) {
        throw new Error("Failed to delete contact");
      }
      setData(response);
    } catch (error) {
      setError(error as Error);
    } finally {
      setIsLoading(false);
    }
  }
  return { deleteContact, data, error, isLoading };
}