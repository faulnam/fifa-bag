# fifa-bag

E-Commerce Storefront & Backoffice for **fifa Bags** — Premium Sustainable Bags, Backpacks, Totes & Accessories.

## Features & Highlights

- **Aesthetic Editorial Design**: Minimalist luxury palette, clean typography, responsive layout across all device viewports.
- **Product Range**:
  - Backpacks & Ransel Commuter (20L, 18L Rolltop & Daypacks)
  - Executive Briefcases & Work Bags
  - Urban Technical Sling Bags
  - Travel Duffle Bags (35L Weekend Bags)
  - Organic Heavyweight Canvas Totes
  - Crescent Shoulder Bags & Luxury Crossbody Bags
  - Fashionable Mini Backpacks
- **Transparent Product Imagery**: High-resolution transparent background PNGs with real-time color variant switching.
- **Sustainability & Material Transparency**: Carbon footprint meters, organic GOTS cotton canvas, AppleSkin™ vegan bio-leather, and RPET recycled materials.
- **Biteship Logistics Integration**: Multi-tier Indonesian domestic shipping calculation (Instant, Regular, Next Day, Cargo).
- **Payment & Order Management**: Automated Midtrans webhook simulation and checkout flows.
- **Interactive AI Assistant (fifa Bag Assistant)**: Conversational guide for bag capacities (liter volume), material care, and product recommendations.
- **Live Search & Filter Sidebar**: Real-time keyword suggestions, capacity/volume filters, price range sliders, and color pickers.

## Installation & Setup

1. **Clone the repository**:
   ```bash
   git clone https://github.com/faulnam/fifa-bag.git
   cd fifa-bag
   ```

2. **Install PHP & Node Dependencies**:
   ```bash
   composer install
   npm install
   ```

3. **Configure Environment (`.env`)**:
   ```env
   APP_NAME="fifa Bag"
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=fifa_bag
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Run Migrations & Seeders**:
   ```bash
   php artisan migrate:fresh --seed
   ```

5. **Start Local Development Server**:
   ```bash
   php artisan serve
   npm run dev
   ```
