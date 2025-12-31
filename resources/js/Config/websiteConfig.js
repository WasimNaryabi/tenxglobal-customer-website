/**
 * 🍔 WEBSITE CONFIGURATION FILE
 * 
 * Change this file to customize your entire website!
 * All components will automatically update.
 */

export default {
  
  // ═══════════════════════════════════════════════════════════
  // 🏢 BUSINESS INFORMATION
  // ═══════════════════════════════════════════════════════════
  business: {
    name: '',
    tagline: 'Delicious Food Delivered Fast',
    logo: '🍔', // Emoji or image path
    logoImage: '/images/smash-n-Grub-logo.jpg', // '/images/logo.png' or null to use emoji
    
    description: 'Delight your taste buds with our delicious food selections. Order now and experience the best flavors.',
    
    established: '2014', // Year business started
    yearsInBusiness: '10+',
    totalCategories: '15+',
    totalItems: '200+',
  },

  // ═══════════════════════════════════════════════════════════
  // 📞 CONTACT INFORMATION
  // ═══════════════════════════════════════════════════════════
  contact: {
    phone: '+441163661935',
    email: 'info@smashngrub.com',
    address: '204 Melbourne Road, Leicester LE2 0DT',
    
    // Social Media
    social: {
      facebook: 'https://facebook.com/yourpage',
      instagram: 'https://instagram.com/yourpage',
      twitter: 'https://twitter.com/yourpage',
      youtube: null, // null to hide
    },
    
    // Business Hours
    hours: {
      weekdays: '4:00 PM - 11:00 PM',
      weekends: '4:00 PM  - 11:00 PM',
      delivery: 'Monday - Sunday: 4PM - 11PM',
    },
  },

  // ═══════════════════════════════════════════════════════════
  // 🎨 BRANDING & COLORS
  // ═══════════════════════════════════════════════════════════
  branding: {
    colors: {
      // Primary Brand Color (Red by default)
      primary: '#ee6626',
      primaryHover: '#c36c45',
      primaryLight: '#fee2e2',
      
     // Secondary Color (Teal/Dark for sections)
      secondary: '#0a4a5c',      // Dark teal
      secondaryHover: '#1e6b7d', // Lighter teal
      
      // Accent Colors
      accent: '#22c55e',         // Green for badges/success
      warning: '#fbbf24',        // Yellow/Gold for prices/highlights
      
      // Dark Mode Colors
      dark: '#111827',           // Almost black (gray-900)
      darkCard: '#1f2937',       // Dark gray for cards (gray-800)
      darkBorder: '#374151',     // Border color (gray-700)
      
      // Neutral Colors
      light: '#f9fafb',          // Light gray
      white: '#ffffff',          // Pure white
      
      // Text Colors (for reference)
      textPrimary: '#ffffff',    // White text
      textSecondary: '#d1d5db',  // Light gray text
      textMuted: '#9ca3af',  
      
      // Neutral Colors
      dark: '#000000',
      light: '#000000',
      white: '#000000',
    },
    
    // Fonts
    fonts: {
      primary: 'Poppins, sans-serif',
      headings: 'Poppins, sans-serif',
    },
  },

  // ═══════════════════════════════════════════════════════════
  // 🏠 HERO SECTION
  // ═══════════════════════════════════════════════════════════
  hero: {
    title: 'Taste Made Easy',
    subtitle: 'Order Online Today',
    description: 'Good food and a good time with your family. We only serve food of the highest quality',
    
    badge: 'Fast & Fresh',
    
    // Hero Image
    image: 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=600&h=600&fit=crop',
    
    // Search Form
    searchPlaceholder: {
      address: 'Enter your address',
      postalCode: 'Postal Code',
    },
    buttonText: 'Start Order',
    
    // Background Gradient
    gradient: 'linear-gradient(135deg, #0a4a5c 0%, #1e6b7d 50%, #2d8ca3 100%)',
  },

  // ═══════════════════════════════════════════════════════════
  // 📋 SECTION HEADINGS
  // ═══════════════════════════════════════════════════════════
  sections: {
    popularItems: {
      title: 'Popular Items',
      subtitle: 'Our most loved dishes',
    },
    
    offers: {
      title: 'Special Offers',
      subtitle: "Don't miss out on our exclusive deals",
    },
    
    newItems: {
      title: 'New Items',
      subtitle: 'Fresh additions to our menu',
    },
    
    features: {
      title: 'Why Choose Us',
      subtitle: 'What makes us special',
    },
    
    trendingMenu: {
      title: 'Trending Food Menu',
      subtitle: 'Most popular dishes this week',
    },
    
    topTrending: {
      title: 'Top Trending',
      subtitle: 'Customer favorites',
    },
    
    fastFood: {
      title: 'Popular Fast Food',
      subtitle: 'Quick bites, big flavors',
    },
    
    about: {
      title: 'Good Food. Great Taste.',
      subtitle: 'Made By Us For You.',
      description: 'We believe in serving only the finest quality food made with fresh ingredients. Our experienced chefs prepare each dish with passion and care, ensuring that every bite is a delightful experience.',
    },
  },

  // ═══════════════════════════════════════════════════════════
  // ✨ FEATURES (What you offer)
  // ═══════════════════════════════════════════════════════════
  features: [
    {
      id: 1,
      icon: 'fast-delivery', // Icon name
      title: 'Fast Delivery',
      description: 'Within 30 minutes',
    },
    {
      id: 2,
      icon: 'online-payment',
      title: 'Online Payment',
      description: '100% secure payment',
    },
    {
      id: 3,
      icon: 'quality',
      title: 'Quality Guarantee',
      description: 'Fresh ingredients',
    },
    {
      id: 4,
      icon: 'support',
      title: '24/7 Available',
      description: 'Always at your service',
    },
  ],

  // ═══════════════════════════════════════════════════════════
  // 🔗 NAVIGATION MENU
  // ═══════════════════════════════════════════════════════════
  navigation: {
    main: [
      { name: 'Home', href: '/', current: true },
      { name: 'Menu', href: '/menu', current: false },
      { name: 'About Us', href: '/about', current: false },
      { name: 'Contact', href: '/contact', current: false },
    ],
    
    footer: {
      about: [
        { name: 'About Us', href: '/about' },
        { name: 'Contact', href: '/contact' },
        { name: 'FAQ', href: '/faq' },
        { name: 'Privacy Policy', href: '/privacy' },
      ],
      menu: [
        { name: 'Chicken Burger', href: '/menu?category=chicken-burger' },
        { name: 'Loaded Fries', href: '/menu?category=loaded-fries' },
        { name: 'Drinks', href: '/menu?category=drinks' },
        { name: 'Kids Meal', href: '/menu?category=kids-meal' },
        { name: 'Full Menu', href: '/menu' },
      ],
    },
  },

  // ═══════════════════════════════════════════════════════════
  // 🎯 CALL-TO-ACTION BUTTONS
  // ═══════════════════════════════════════════════════════════
  buttons: {
    orderNow: 'Order Now',
    viewMore: 'View More',
    viewAllMenu: 'View All Menu',
    addToCart: 'Add to Cart',
    readMore: 'Read More',
    subscribe: 'Subscribe',
    checkout: 'Proceed to Checkout',
    clearCart: 'Clear Cart',
    exploreMenu: 'Explore Menu',
    login: 'Log in',
  },

  // ═══════════════════════════════════════════════════════════
  // 📰 NEWSLETTER
  // ═══════════════════════════════════════════════════════════
  newsletter: {
    title: 'Newsletter',
    description: 'Subscribe to get special offers and updates',
    placeholder: 'Your email',
    buttonText: 'Subscribe',
    successMessage: 'Thank you for subscribing!',
  },

  // ═══════════════════════════════════════════════════════════
  // 🛒 SHOPPING CART
  // ═══════════════════════════════════════════════════════════
  cart: {
    title: 'Shopping Cart',
    emptyMessage: 'Your cart is empty',
    emptyDescription: 'Add some delicious items to get started!',
    total: 'Total',
    checkout: 'Proceed to Checkout',
    continueShopping: 'Continue Shopping',
  },

  // ═══════════════════════════════════════════════════════════
  // 🎨 BADGES & LABELS
  // ═══════════════════════════════════════════════════════════
  badges: {
    new: 'NEW',
    hot: 'HOT',
    sale: 'SALE',
    popular: 'POPULAR',
    featured: 'FEATURED',
  },

  // ═══════════════════════════════════════════════════════════
  // 💬 MESSAGES & NOTIFICATIONS
  // ═══════════════════════════════════════════════════════════
  messages: {
    addedToCart: 'Added to cart successfully!',
    removedFromCart: 'Removed from cart',
    cartCleared: 'Cart cleared',
    orderPlaced: 'Order placed successfully!',
    newsletterSubscribed: 'Thank you for subscribing!',
    error: 'Something went wrong. Please try again.',
  },

  // ═══════════════════════════════════════════════════════════
  // 📸 IMAGES & MEDIA
  // ═══════════════════════════════════════════════════════════
  images: {
    // Default placeholder images
    placeholder: '/images/placeholder.jpg',
    
    // About section image
    aboutImage: 'https://images.unsplash.com/photo-1513104890138-7c749659a591?w=600&h=600&fit=crop',
    
    // Default food images (fallback)
    defaultFood: 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=400&h=400&fit=crop',
  },

  // ═══════════════════════════════════════════════════════════
  // ⚙️ WEBSITE SETTINGS
  // ═══════════════════════════════════════════════════════════
  settings: {
    // Currency
    currency: {
      symbol: '£',
      code: 'GBP',
      position: 'before', // 'before' or 'after'
    },
    
    // Delivery
    delivery: {
      minOrder: 15, // Minimum order amount
      deliveryFee: 5, // Delivery fee
      freeDeliveryAbove: 50, // Free delivery threshold
      estimatedTime: '30-45 mins',
    },
    
    // App behavior
    behavior: {
      showPrices: true,
      enableCart: true,
      enableWishlist: false,
      enableReviews: true,
      enableSearch: true,
    },
  },

  // ═══════════════════════════════════════════════════════════
  // 📱 SEO & META
  // ═══════════════════════════════════════════════════════════
  seo: {
    title: 'FoodHub - Order Delicious Food Online',
    description: 'Order fresh, delicious food online from FoodHub. Fast delivery, quality ingredients, and amazing taste!',
    keywords: 'food delivery, online food order, restaurant, pizza, burgers, fast food',
    author: 'FoodHub Team',
  },

  // ═══════════════════════════════════════════════════════════
  // 🌍 LOCALIZATION
  // ═══════════════════════════════════════════════════════════
  locale: {
    language: 'en',
    timezone: 'GMT/BST',
    dateFormat: 'MM/DD/YYYY',
    timeFormat: '12h', // '12h' or '24h'
  },
};