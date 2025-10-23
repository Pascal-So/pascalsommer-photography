import Comment from "./Comment.tsx";

export default function Comments() {
  const id = 1234;
  const date: Date = new Date("2023-07-14");
  const author = "Pascal";
  const content =
    "asdfffffff\n\nmultiline comment with &gt; <span>stuff</span>";

  return (
    <>
      <Comment id={id} date={date} author={author} content={content} />

      <Comment id={id + 1} date={date} author={author} content={content} />
    </>
  );
}
