import { expect, test } from "vitest";
import { parseDescription, serializePlain } from "../utils.ts";

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

test("serialize short coords", () => {
  const desc = "#coords47°00'00\"N 7°00'00\"E#";
  expect(parseDescription(desc)).toStrictEqual([
    { linktype: "coords", latlng: "47°00'00\"N 7°00'00\"E" },
  ]);
});

test("serialize long coords", () => {
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
