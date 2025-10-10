import { file } from "astro/loaders";
import { defineCollection, z } from "astro:content";

const posts = defineCollection({
  loader: file("src/data/example-posts/data.json", { parser: (text) => JSON.parse(text).posts }),
  schema: ({image}) => z.object({
    name: z.string(),
    date: z.string().transform((str) => new Date(str)),
    photos: z.array(z.object({
      id: z.number(),
      description: z.string(),
      img: image(),
    })),
  }),
});

export const collections = { posts }
