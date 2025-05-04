// バックエンドapiからcontactsにリクエストするためのcustom hook

export const useContact = () => {
  const fetchContacts = async () => {
    const res = await fetch("https://jsonplaceholder.typicode.com/users");
    if (!res.ok) {
      throw new Error("Failed to fetch data");
    }
    return res.json();
  };

  return { fetchContacts };
}

/**
 * 
 * @param id 
 * @returns { DataTransfer, error: Error, isLoading: boolean }
 */
export const useSingleContact = (id: string) => {
  const fetchContact = async () => {
    const res = await fetch(`https://jsonplaceholder.typicode.com/users/${id}`);
    console.log(res);
    
    if (!res.ok) {
      throw new Error("Failed to fetch data");
    }
    return res.json();
  };
  return { fetchContact };
}