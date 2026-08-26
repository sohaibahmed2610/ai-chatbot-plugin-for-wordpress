# AI Chatbot WordPress Plugin

![License: GPL v2](https://img.shields.io/badge/License-GPL%20v2-blue.svg)
![Tested up to](https://img.shields.io/badge/Tested%20up%20to-6.4-green.svg)
![PHP Version Require](https://img.shields.io/badge/PHP-%3E%3D%207.4-blue.svg)

> ⭐ **Love this plugin?** Please consider starring the repository to show your support!

## Overview
A production-ready WordPress plugin that integrates intelligent, highly customizable floating chatbots powered by OpenAI, Google Gemini, and Anthropic. Deliver instant, automated AI support to your visitors without writing a single line of code.

<img width="1859" height="859" alt="{FED5391D-20C1-4337-9E90-D267D8BC1693}" src="https://github.com/user-attachments/assets/356bf5e1-1ee0-4850-a62e-93ff460184ae" />


## 🔗 Quick Links
- [Report a Bug](https://github.com/sohaibahmed2610/ai-chatbot-plugin-for-wordpress/issues)

## 📑 Table of Contents
- [Features](#-features)
- [Tech Stack](#-tech-stack)
- [Getting Started](#-getting-started)
  - [Prerequisites](#prerequisites)
  - [Installation](#installation)
  - [API Configuration](#api-configuration)

## 🌟 Features

### Core Functionality
- **Multi-Provider Support**: Seamlessly connect to OpenAI, Google Gemini, or Anthropic.
- **Context-Aware Sessions**: Maintains conversation history throughout the visitor's active session.
- **Zero-Code Configuration**: Full management via a native WordPress settings dashboard.

### Design & Customization
- **Theme Builder**: Customize primary, background, and message colors.
- **Visual Identity**: Upload a custom chat icon directly from the WordPress Media Library.
- **Adaptive Positioning**: Anchor to the bottom-left or bottom-right with custom offsets.

### Security & Performance
- **Protected Endpoints**: API keys remain securely on the server and are never exposed to the client.
- **Abuse Prevention**: Built-in IP-based rate limiting on the REST API.
- **Lightweight Architecture**: Vanilla JavaScript and CSS frontend ensures zero dependency bloat.

## 💻 Tech Stack

| Category | Technology |
| :--- | :--- |
| **Backend Framework** | WordPress Native (Settings API, REST API, HTTP API), PHP 7.4+ |
| **Frontend Styling** | Scoped Vanilla CSS with dynamic CSS variables |
| **Frontend Logic** | Vanilla ES6 JavaScript |
| **AI Providers** | OpenAI API, Google Gemini API, Anthropic API |

## 🚀 Getting Started

### Prerequisites
- WordPress 5.8 or higher
- PHP 7.4 or higher
- An active API key from OpenAI, Google AI Studio, or Anthropic

### Installation
1. Download the latest release `.zip` file from the [Releases](https://github.com/sohaibahmed2610/ai-chatbot-plugin-for-wordpress/releases) page.
2. Log in to your WordPress Admin dashboard.
3. Navigate to **Plugins > Add New > Upload Plugin**.
4. Select the downloaded `.zip` file and click **Install Now**.
5. Click **Activate Plugin**.

### API Configuration
1. Navigate to the new **AI Chatbot** menu in your WordPress sidebar.
2. Select your desired **AI Provider** from the dropdown menu (gemini , open ai , clade).
3. Enter your provider's **API Key** and the specific **Model** you wish to use (e.g., gpt-4o-mini , gemini 3.1 flash live etc).
4. (Optional) Provide custom **Instructions** to define the chatbot's persona.
5. Click **Save Changes**. The chatbot will now automatically appear on your website!
