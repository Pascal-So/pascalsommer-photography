import { expect, test } from "vitest";
import { generateLink, parseDescription, serializePlain } from "../utils.ts";

test("parse plain description", () => {
  const desc = "asdf";
  expect(parseDescription(desc)).toStrictEqual([desc]);
});

test("parse empty description", () => {
  expect(parseDescription("")).toStrictEqual([]);
});

test("parse description with photo link", () => {
  const desc = "asdf #photo55# hello";
  expect(parseDescription(desc)).toStrictEqual([
    "asdf ",
    { linktype: "photo", id: 55 },
    " hello",
  ]);
});

test("parse description with multiple photo link", () => {
  const desc = "asdf #photo55# hello#photo2#";
  expect(parseDescription(desc)).toStrictEqual([
    "asdf ",
    { linktype: "photo", id: 55 },
    " hello",
    { linktype: "photo", id: 2 },
  ]);
});

test("parse description with different link types", () => {
  const desc = "asdf #photo55# hello#post2#";
  expect(parseDescription(desc)).toStrictEqual([
    "asdf ",
    { linktype: "photo", id: 55 },
    " hello",
    { linktype: "post", id: 2 },
  ]);
});

test("parse short coords", () => {
  const desc = "#coords47°00'00\"N 7°00'00\"E#";
  expect(parseDescription(desc)).toStrictEqual([
    { linktype: "coords", latlng: "47°00'00\"N 7°00'00\"E" },
  ]);
});

test("parse long coords", () => {
  const desc = "#coords54°30'00.2\"N 11°13'36.4\"E#";
  expect(parseDescription(desc)).toStrictEqual([
    { linktype: "coords", latlng: "54°30'00.2\"N 11°13'36.4\"E" },
  ]);
});

test("serialize plain with just string", () => {
  expect(serializePlain(["asdflkj"])).toStrictEqual("asdflkj");
});
test("serialize plain with links", () => {
  expect(
    serializePlain([
      "asdf ",
      { linktype: "photo", id: 55 },
      " hello",
      { linktype: "photo", id: 2 },
    ]),
  ).toStrictEqual("asdf 55 hello2");
});

test("generate photo link", () => {
  const link = generateLink.photo(35);
  expect(link).toStrictEqual("/photos/35");
});
test("generate post link from string id", () => {
  const link = generateLink.post("10");
  expect(link).toStrictEqual("/post/10");
});
test("generate post link from number id", () => {
  const link = generateLink.post(10);
  expect(link).toStrictEqual("/post/10");
});
test("generate page link with anchor", () => {
  const link = generateLink.page(3, "post-10");
  expect(link).toStrictEqual("/page/3#post-10");
});
test("generate tag link with one single tag", () => {
  const link = generateLink.tag("Architecture");
  expect(link).toStrictEqual("/tag/Architecture");
});
