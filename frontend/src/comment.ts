export async function getCommentToken(
  backend_url: string,
  photoId: number,
): Promise<string> {
  const response = await fetch(
    `${backend_url}/api/photos/${photoId}/commenttoken`,
  );
  const data = await response.json();
  return data.token;
}

export async function submitComment(
  backendUrl: string,
  name: string,
  content: string,
  photoId: number,
  token: string,
): Promise<void> {
  const response = await fetch(`${backendUrl}/api/photos/${photoId}/comment`, {
    method: "POST",
    body: new URLSearchParams({
      name,
      comment: content,
      _token: token,
    }),
  });

  if (response.status !== 200) {
    throw new Error(await response.text());
  }
}
