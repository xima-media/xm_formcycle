import { test, expect, Page, FrameLocator } from '@playwright/test';

const BE_USER = 'admin';
const BE_PASSWORD = 'Passw0rd!';

async function login(page: Page): Promise<void> {
  await page.goto('/typo3/');
  await page.fill('input[name="username"]', BE_USER);
  await page.fill('input[type="password"]', BE_PASSWORD);
  await page.click('button[type="submit"]');
  await page.waitForSelector('.scaffold-content', { timeout: 30000 });
}

/**
* Opens the "new content element" wizard on the root page and returns the
* wizard web component locator. Playwright pierces the shadow DOM automatically,
* so the wizard contents are reachable through a normal locator.
*/
async function openContentWizard(page: Page): Promise<{ wizard: ReturnType<Page['locator']>; contentFrame: FrameLocator }> {
  // Open the Page module
  await page.click('[data-modulemenu-identifier="web_layout"]');

  // Select the root page in the page tree
  await page.waitForSelector('#typo3-pagetree-treeContainer .node-contentlabel', { timeout: 30000 });
  await page.locator('#typo3-pagetree-treeContainer .node-contentlabel', { hasText: 'Main' }).first().click();

  // The module content lives in the list_frame iframe
  const contentFrame = page.frameLocator('[name="list_frame"]');
  await contentFrame.locator('typo3-backend-new-content-element-wizard-button').first().click();

  // The wizard modal renders at the top document level
  const wizard = page.locator('typo3-backend-new-record-wizard');
  await expect(wizard).toBeVisible({ timeout: 30000 });

  // Switch to the "Form elements" tab which holds the formcycle element
  await wizard.getByRole('tab', { name: /Form elements/ }).click();

  return { wizard, contentFrame };
}

test.describe('Formcycle content element', () => {
  test.beforeEach(async ({ page }) => {
    await login(page);
  });

  test('is available in the new content element wizard', async ({ page }) => {
    const { wizard } = await openContentWizard(page);

    const formcycleButton = wizard.locator('button[data-identifier="forms_formcycle"]');
    await expect(formcycleButton).toBeVisible();
    await expect(formcycleButton).toContainText('Include a formcycle form');
  });

  test('renders the form selection element and loads forms from the server', async ({ page }) => {
    const { wizard, contentFrame } = await openContentWizard(page);

    // Create a new formcycle content element
    await wizard.locator('button[data-identifier="forms_formcycle"]').click();

    // Fill the header and open the Formcycle tab inside the element
    await contentFrame.locator('input[data-formengine-input-name*="[header]"]').fill('Playwright Form Element');
    await contentFrame.getByText('Formcycle', { exact: true }).first().click();

    // No configuration error must be shown
    await expect(contentFrame.getByText('Configuration error')).toHaveCount(0);

    // The available forms (loaded via AJAX from the formcycle server) must appear.
    // The remote call can be slow, so allow a generous timeout.
    await expect(contentFrame.locator('#xm-available-forms-wrapper')).toBeVisible({ timeout: 120000 });
  });
});
