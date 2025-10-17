export function formatDate(date: Date): string {
  return date.toISOString().split("T")[0];
}

export type DescriptionElement =
  | string
  | { linktype: "post"; id: number }
  | { linktype: "photo"; id: number }
  | { linktype: "coords"; latlng: string };

export function parseDescription(desc: string): DescriptionElement[] {
  const regex = /#(photo|post)(\d+)#/g;
  const matches = [...desc.matchAll(regex)];

  const segments = [];
  let lastIndex = 0;

  matches.forEach((match) => {
    const textBetweenMatches = desc.slice(lastIndex, match.index);
    if (textBetweenMatches !== "") {
      segments.push(textBetweenMatches);
    }

    segments.push({ linktype: match[1], id: parseInt(match[2]) }); // matched segment
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
