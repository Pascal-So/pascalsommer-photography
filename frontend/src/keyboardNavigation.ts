function activateKeyboardNavigation() {
  const elements = document.querySelectorAll("[data-shortcut-key]");
  const htmlElements = elements
    .values()
    .filter((el): el is HTMLElement => el instanceof HTMLElement);
  const keyMap = new Map(
    htmlElements.map((el) => [el.getAttribute("data-shortcut-key"), el]),
  );

  document.onkeydown = (evt) => {
    if (evt.ctrlKey || evt.altKey || evt.shiftKey) {
      return;
    }

    // Don't do anything if a textfield is in focus.
    if (document.activeElement) {
      const tagName = document.activeElement.tagName.toLowerCase();

      if (tagName == "input" || tagName == "textarea") {
        return;
      }
    }

    const el = keyMap.get(evt.key);
    if (el !== undefined) {
      el.click();
    }
  };
}

activateKeyboardNavigation();
