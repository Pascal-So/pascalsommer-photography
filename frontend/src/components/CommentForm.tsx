import { useState } from "react";
import { useForm, type SubmitHandler } from "react-hook-form";

export type CommentFormProps = {
  submitComment: (name: string, content: string) => Promise<void>;
};

type Inputs = {
  name: string;
  content: string;
};

export default function CommentForm({ submitComment }: CommentFormProps) {
  const [error, setError] = useState<string | null>(null);
  const {
    register,
    handleSubmit,
    formState: { isSubmitting },
    reset,
  } = useForm<Inputs>({ progressive: true });

  const onSubmit: SubmitHandler<Inputs> = async (data) => {
    setError(null);

    // We make sure that the submit function runs for at least one cycle of the
    // swoosh animation.
    const delay = new Promise((res) => setTimeout(res, 600));
    let success = false;

    try {
      await submitComment(data.name, data.content);
      success = true;
    } catch (e) {
      console.warn("error while submitting comment", e);
      const message = e instanceof Error ? e.message : String(e);

      setError(message);
    }

    await delay;
    if (success) {
      reset();
    }
  };

  return (
    <div className="card relative overflow-hidden">
      <form className="" onSubmit={handleSubmit(onSubmit)}>
        {error !== null && (
          <div className="card bg-[#20131080] mb-3">
            Could not submit comment. {error}
          </div>
        )}

        <input
          className="mb-3"
          placeholder="Name"
          {...register("name", { required: true, maxLength: 100 })}
        />
        <br />

        <textarea
          className="mb-3"
          placeholder="Comment"
          {...register("content", { required: true, maxLength: 1990 })}
          rows={4}
        ></textarea>
        <br />

        <div className="w-fill flex justify-end">
          <input
            className="button active:bg-zinc-800"
            type="submit"
            value={"Post Comment"}
          />
        </div>
      </form>

      {isSubmitting && <div className="loading-cover" />}
    </div>
  );
}
