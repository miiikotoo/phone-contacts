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
    throw new Error(`HTTP error! status: ${res.status}`);
  }

  // DELETEリクエストの場合、レスポンスが空の可能性がある
  const contentType = res.headers.get("content-type");
  if (contentType && contentType.includes("application/json")) {
    return res.json();
  }
  return null; // 空のレスポンスの場合はnullを返す
}
