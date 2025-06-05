// 電話番号のリクエストを処理するAPI関数
import { fetcher } from '../../_lib/fetcher';

const API_BASE_URL = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:61221';

export const createPhone = (contactId: number, number: string) => {
  return fetcher(`${API_BASE_URL}/api/phones/create`, 'POST', {
    contact_id: contactId,
    number: number
  });
};

export const updatePhone = (id: number, number: string) => {
  return fetcher(`${API_BASE_URL}/api/phones/${id}/update`, 'PUT', {
    number: number
  });
};

export const deletePhone = (id: number) => {
  return fetcher(`${API_BASE_URL}/api/phones/${id}/delete`, 'DELETE');
};