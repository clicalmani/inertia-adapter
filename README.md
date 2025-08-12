# Tonka PHP Framework Inertia.js Adapter

## Overview

The Tonka PHP Framework Inertia.js Adapter allows you to seamlessly integrate Inertia.js with the Tonka PHP Framework. This adapter enables you to build modern single-page applications (SPAs) while leveraging server-side rendering and routing.

## Features

- **Easy Integration**: Quickly set up Inertia.js with Tonka.
- **Automatic Responses**: Handle Inertia responses directly from your controllers.
- **Supports Multiple Frameworks**: Works with Vue.js, React, and Svelte.
- **Navigation Handling**: Simplified link handling and navigation management.
- **Error Handling**: Built-in support for error pages and validation messages.

## Installation

You can install the Tonka Inertia.js Adapter via Composer:

```bash
composer require inertia/tonka-adapter
```

## Client-Side Setup:

Make sure to install the Inertia.js client-side adapter for your chosen framework:

```bash
npm install @inertiajs/inertia @inertiajs/inertia-vue
```

## Usage:

Create a Controller to handle Inertia responses:

```php
use Inertia\Inertia;

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard', [
        'user' => auth()->user(),
        'notifications' => Notification::recent(),
    ]);
});
```

## Client-Side Example:

```vue
<template>
    <div>
        <h1>{{ message }}</h1>
    </div>
</template>

<script>
export default {
    props: {
        message: String
    }
}
</script>
```

## Contributing:

Contributions are welcome! Please open an issue or submit a pull request.

1. Fork the repository.
2. Create your feature branch: git checkout -b feature/my-new-feature
3. Commit your changes: git commit -m 'Add some feature'
4. Push to the branch: git push origin feature/my-new-feature
5. Open a Pull Request.

## License:

This project is licensed under the MIT License - see the LICENSE file for details.

## Contact:

For any inquiries, please reach out to <a href="https://fb.com/clicalmani" target="blank"><img align="center" src="https://raw.githubusercontent.com/rahuldkjain/github-profile-readme-generator/master/src/images/icons/Social/facebook.svg" alt="clicalmani" height="30" width="40" /></a>