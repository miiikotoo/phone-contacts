// 電話番号のリクエストを管理するカスタムフック
import { useState } from 'react';
import { createPhone, updatePhone, deletePhone } from '../api/phoneApi';

export const usePhone = () => {
  const [isLoading, setIsLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);

  const handleApiCall = async (
    apiCall: () => Promise<any>
  ) => {
    setIsLoading(true);
    setError(null);
    
    try {
      await apiCall();
      return { success: true, error: null };
    } catch (error: unknown) {
      const errorMsg = error instanceof Error ? error.message : '予期せぬエラーが発生しました';
      setError(errorMsg);
      return { success: false, error: errorMsg };
    } finally {
      setIsLoading(false);
    }
  };

  const createPhoneNumber = async (contactId: number, number: string) => {
    return handleApiCall(() => createPhone(contactId, number));
  };

  const updatePhoneNumber = async (id: number, number: string) => {
    return handleApiCall(() => updatePhone(id, number));
  };

  const deletePhoneNumber = async (id: number) => {
    return handleApiCall(() => deletePhone(id));
  };

  return {
    createPhoneNumber,
    updatePhoneNumber,
    deletePhoneNumber,
    isLoading,
    error
  };
};