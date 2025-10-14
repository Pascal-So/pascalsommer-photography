import { file } from "astro/loaders";
import { defineCollection, z } from "astro:content";
import mysql, { type ConnectionOptions } from "mysql2/promise";
import fs from "node:fs/promises";

const importFromDB = false;
if (importFromDB) {
  const connOptions: ConnectionOptions = {
    user: import.meta.env.DB_USER,
    database: import.meta.env.DB_DATABASE,
    password: import.meta.env.DB_PASSWORD,
    host: import.meta.env.DB_HOST,
  };

  const conn = await mysql.createConnection(connOptions);

  const photos = (
    await conn.query("select * from photos order by weight")
  )[0] as any;
  const posts = (await conn.query("select * from posts"))[0] as any;
  const postsMap = Object.fromEntries(
    posts.map((post: any) => [
      post.id.toString(),
      { id: post.id.toString(), name: post.title, date: post.date, photos: [] },
    ]),
  );
  for (const photo of photos) {
    if (photo.post_id !== null) {
      postsMap[photo.post_id.toString()].photos.push({
        id: photo.id,
        description: photo.description,
        img: `/src/data/real-data/img/${photo.path}`,
      });
    }
  }
  const postsCollection = Object.values(postsMap);
  await fs.writeFile(
    "src/data/real-data/data.json",
    JSON.stringify({ posts: postsCollection }),
  );
}

const posts = defineCollection({
  loader: file("src/data/real-data/data.json", {
    parser: (text) => JSON.parse(text).posts,
  }),
  schema: ({ image }) =>
    z.object({
      name: z.string(),
      date: z.string().transform((str) => new Date(str)),
      photos: z.array(
        z.object({
          id: z.number(),
          description: z.string(),
          img: image(),
        }),
      ),
    }),
});

export const collections = { posts };
