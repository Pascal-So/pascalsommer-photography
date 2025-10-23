import { useEffect, useState } from "react";
import Comment from "./Comment.tsx";

export type CommentsProps = {
  backendUrl: string;
  photo: number;
};

type CommentsState = "loading" | "error" | [any];

export default function Comments({ backendUrl, photo }: CommentsProps) {
  const [comments, setComments] = useState<CommentsState>("loading");

  useEffect(() => {
    setComments("loading");
    fetch(`${backendUrl}/api/photos/${photo}/comments`)
      .then((response) => response.json())
      .then((json) => setComments(json))
      .catch((err) => {
        console.warn(`could not fetch comments for photo ${photo}`, err);
        setComments("error");
      });
  }, [photo, setComments, backendUrl]);

  if (comments === "loading") {
    return <span>Loading Comments...</span>;
  } else if (comments === "error") {
    return <div></div>;
  }

  return (
    <div>
      {comments.map((comment) => (
        <Comment
          id={comment.id}
          author={comment.name}
          content={comment.comment}
          date={new Date(comment.created_at)}
          key={comment.id}
        />
      ))}
    </div>
  );
}
