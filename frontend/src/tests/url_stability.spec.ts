import { test, expect } from '@playwright/test';

// These tests are here to ensure that urls from the old website still work on
// the new website.

test('homepage', async ({ page }) => {
  await page.goto('/');

  await expect(page).toHaveTitle(/Pascal Sommer/);

  // post permalinks
  const postPermalinks = page.getByRole('link').filter({hasText: '#'});
  expect(postPermalinks.first()).toBeInViewport();
  const nrPermalinks = await postPermalinks.count();
  expect(nrPermalinks).toBeGreaterThan(3);

  for (let i = 0; i < nrPermalinks; i++) {
    const el = postPermalinks.nth(i);
    expect(await el.getAttribute('href')).toMatch(/\/post\/\d+$/);
  }

  // photos
  const photos = await page.getByRole('link').filter({has: page.getByRole('img')}).all();
  expect(photos.length).toBeGreaterThan(5);
  for (const photo of photos) {
    const bb = await photo.boundingBox();
    if (!bb || bb.width < 100) {
      continue;
    }

    const href = await photo.getAttribute('href');
    expect(href).toMatch(/\/photos\/\d+$/);
  }
});

test('photo', async ({page}) => {
  await page.goto('/photos/1');
  await expect(page.getByText('Photo 1:')).toBeVisible();
});

test('can visit posts directly', async ({page, baseURL}) => {
  await page.goto('/post/1');

  expect(baseURL).toBeDefined();
  // should redirect to the page where the post is and include an anchor
  await page.waitForURL(url => !url.pathname.includes('post') && url.hash.length > 1);
});
