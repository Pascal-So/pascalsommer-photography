import { useEffect, useState } from "react";
import Comment from "./Comment.tsx";
import CommentForm from "./CommentForm.tsx";
import { getCommentToken, submitComment } from "../comment.ts";

export type CommentsProps = {
  backendUrl: string;
  photo: number;
};

type CommentsState = "loading" | "reloading" | "error" | [{id: number, name: string, comment: string, created_at: string}];

export default function Comments({ backendUrl, photo }: CommentsProps) {
  const [comments, setComments] = useState<CommentsState>("loading");

  async function loadComments() {
    await fetch(`${backendUrl}/api/photos/${photo}/comments`)
      .then((response) => response.json())
      .then((json) => setComments(json))
      .catch((err) => {
        console.warn(`could not fetch comments for photo ${photo}`, err);
        setComments("error");
      });
  }

  useEffect(() => {
    loadComments();
  }, [photo, setComments, backendUrl]);

  if (comments === "loading") {
    return <span>Loading Comments...</span>;
  } else if (comments === "error") {
    return <div></div>;
  }

  async function submit(name: string, content: string) {
    const token = await getCommentToken(backendUrl, photo);
    await submitComment(backendUrl, name, content, photo, token);
    setComments("reloading");
    loadComments();
  }

  return (
    <div>
      {comments !== "reloading" &&
        comments.map((comment) => (
          <Comment
            id={comment.id}
            author={comment.name}
            content={comment.comment}
            date={new Date(comment.created_at)}
            key={comment.id}
          />
        ))}

      <CommentForm submitComment={submit} />
    </div>
  );
}
