import { escapeHtml, formatDate, nl2br } from "../utils";

export type CommentProps = {
  id: number;
  date: Date;
  author: string;
  content: string;
};

export default function Comment({ id, date, author, content }: CommentProps) {
  return (
    <div className="card mb-[15px]">
      <p className="mb-1">
        Comment by{" "}
        <a href={`#comment_${id}`} id={`comment_${id}`}>
          {author}
        </a>
        {" - "}
        {formatDate(date)}
      </p>

      <p dangerouslySetInnerHTML={{ __html: nl2br(escapeHtml(content)) }} />
    </div>
  );
}
