// fetcher.ts

/**
 * 
 * @param url 
 * @param method 
 * @param data 
 * @returns { fetcher: () => Promise<void>, error: Error, isLoading: boolean }
 * @description fetcher function
 * @example
 * const { fetcher, error, isLoading } = useFetcher(url, method, data);
 */
export const fetcher = async (
  url: string,
  method: string = "GET",
  data: any = null
) => {
  const res = await fetch(url, {
    mode: "cors",
    method: method,
    credentials: "include",
    headers: {
      "Content-Type": "application/json",
    },
    body: data ? JSON.stringify(data) : null,
  });
  if (!res.ok) {
    throw new Error("Failed to fetch data");
  }
  return res.json();
}
