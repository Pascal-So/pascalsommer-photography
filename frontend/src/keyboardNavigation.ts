function activateKeyboardNavigation() {
  const elements = document.querySelectorAll("[data-shortcut-key]");
  const htmlElements = elements
    .values()
    .filter((el): el is HTMLElement => el instanceof HTMLElement);
  const keyMap = new Map(
    htmlElements.map((el) => [el.getAttribute("data-shortcut-key"), el]),
  );

  document.onkeyup = (evt) => {
    const el = keyMap.get(evt.key);
    if (el !== undefined) {
      el.click();
    }
  };
}

activateKeyboardNavigation();

