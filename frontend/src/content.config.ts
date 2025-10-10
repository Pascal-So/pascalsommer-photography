import { file } from "astro/loaders";
import { defineCollection, reference, z } from "astro:content";

const posts = defineCollection({
  loader: file("src/data/example-posts/data.json", { parser: (text) => JSON.parse(text).posts }),
  schema: z.object({
    name: z.string(),
    date: z.string().transform((str) => new Date(str)),
    photos: z.array(reference("photos")),
  }),
});

const photos = defineCollection({
  loader: file("src/data/example-posts/data.json", { parser: (text) => JSON.parse(text).photos }),
  schema: z.object({
    post: reference("posts"),
    description: z.string(),
    img: z.string(),
  }),
});

export const collections = { posts, photos }
