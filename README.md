<div align="center">
  <img src="public/images/logo-icon.png" alt="Logo" width="150" height="150">
  <h3 align="center">FileFusion</h3>

  <p align="center">
    Manage All Your Cloud Storage in One Place.
    <br />
    <a href="#about-the-project"><strong>Explore the docs »</strong></a>
    <br />
    <br />
    <a href="#">View Demo</a>
    ·
    <a href="#">Report Bug</a>
    ·
    <a href="#">Request Feature</a>
  </p>
</div>

<!-- ABOUT THE PROJECT -->
## About The Project

![FileFusion Dashboard](public/images/dashboard.png)

FileFusion is a powerful multi-cloud storage platform that allows you to connect and manage all your files seamlessly from Google Drive, OneDrive, and more. Say goodbye to jumping between different tabs and apps. FileFusion gives you one unified, beautiful dashboard for all your cloud storage needs.

### 🌟 Key Features

*   ⚡ **Lightning Fast:** Access and manage files across all your clouds instantly with zero lag.
*   🔒 **Bank-Grade Security:** End-to-end encryption keeps your data safe across every cloud provider.
*   ☁️ **Multi-Cloud Unified:** One dashboard for Google Drive, OneDrive, and more — beautifully unified.
*   🔄 **Real-Time Sync:** Changes sync automatically across all connected storage providers.

### 🛠️ Built With

*   ![Laravel](https://img.shields.io/badge/laravel-%23FF2D20.svg?style=for-the-badge&logo=laravel&logoColor=white)
*   ![Vue.js](https://img.shields.io/badge/vuejs-%2335495e.svg?style=for-the-badge&logo=vuedotjs&logoColor=%234FC08D)
*   ![Bootstrap](https://img.shields.io/badge/bootstrap-%238511FA.svg?style=for-the-badge&logo=bootstrap&logoColor=white)
*   ![MySQL](https://img.shields.io/badge/mysql-%2300f.svg?style=for-the-badge&logo=mysql&logoColor=white)

<!-- GETTING STARTED -->
## Getting Started

To get a local copy up and running, follow these simple steps.

### Prerequisites

*   PHP >= 8.2
*   Composer
*   Node.js & npm
*   MySQL or SQLite

### Installation

1.  **Clone the repo**
    ```sh
    git clone https://github.com/your_username/FileFusion-Multi-Cloud-Storage-App.git
    ```
2.  **Install PHP Dependencies**
    ```sh
    composer install
    ```
3.  **Install NPM Packages**
    ```sh
    npm install
    ```
4.  **Copy the environment file**
    ```sh
    cp .env.example .env
    ```
5.  **Generate application key**
    ```sh
    php artisan key:generate
    ```
6.  **Configure Database & Cloud Providers**
    Update your `.env` file with your database credentials and API keys for Google Drive and OneDrive.
    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=filefusion
    DB_USERNAME=root
    DB_PASSWORD=

    GOOGLE_DRIVE_CLIENT_ID=your_client_id
    GOOGLE_DRIVE_CLIENT_SECRET=your_client_secret
    ```
7.  **Run Migrations**
    ```sh
    php artisan migrate
    ```
8.  **Compile Assets & Run Server**
    ```sh
    npm run dev
    php artisan serve
    ```

<!-- ROADMAP -->
## Roadmap

- [x] Integrate Google Drive API
- [x] Integrate OneDrive API
- [ ] Add Dropbox Support
- [ ] Implement File Sharing functionality
- [ ] Advanced Search across all clouds

<!-- CONTRIBUTING -->
## Contributing

Contributions are what make the open source community such an amazing place to learn, inspire, and create. Any contributions you make are **greatly appreciated**.

<!-- LICENSE -->
## License

Distributed under the MIT License. See `LICENSE` for more information.

<!-- CONTACT -->
## Contact

Project Link: [https://github.com/your_username/FileFusion-Multi-Cloud-Storage-App](https://github.com/your_username/FileFusion-Multi-Cloud-Storage-App)
