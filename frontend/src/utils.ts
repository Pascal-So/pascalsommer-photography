export function formatDate(date: Date): string {
  return date.toISOString().split("T")[0]!;
}

export type DescriptionElement =
  | string
  | { linktype: "post"; id: number }
  | { linktype: "photo"; id: number }
  | { linktype: "coords"; latlng: string };

export function parseDescription(desc: string): DescriptionElement[] {
  const regex = /#((photo|post)(\d+)|(coords)(.{18,26}))#/g;
  const matches = [...desc.matchAll(regex)];

  const segments = [];
  let lastIndex = 0;

  matches.forEach((match) => {
    const textBetweenMatches = desc.slice(lastIndex, match.index);
    if (textBetweenMatches !== "") {
      segments.push(textBetweenMatches);
    }

    // check the type of the matched segment
    if (match[2] === "photo" || match[2] === "post") {
      const linktype = match[2];
      const id = parseInt(match[3]!);
      segments.push({ linktype, id });
    } else if (match[4] === "coords") {
      segments.push({ linktype: match[4], latlng: match[5] });
    }
    lastIndex = match.index + match[0].length;
  });

  const textAfterLastMatch = desc.slice(lastIndex);
  if (textAfterLastMatch !== "") {
    segments.push(textAfterLastMatch);
  }

  return segments;
}

export function serializePlain(elements: DescriptionElement[]): string {
  return "".concat(
    ...elements.map((el) => {
      if (typeof el === "string") {
        return el;
      } else if (el.linktype === "coords") {
        return el.latlng;
      } else {
        return el.id.toString();
      }
    }),
  );
}

export function escapeHtml(unsafe: string) {
  return unsafe
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;")
    .replace(/'/g, "&#039;");
};

export function nl2br(string: string) {
  return string.replace(/\n/g, "<br>");
};
