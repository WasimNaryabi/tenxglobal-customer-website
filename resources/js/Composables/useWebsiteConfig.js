import { computed } from 'vue';
import websiteConfig from '@/Config/websiteConfig';

/**
 * Composable to access website configuration
 * Use this in any component to get website settings
 */
export function useWebsiteConfig() {
  
  // Business Info
  const business = computed(() => websiteConfig.business);
  const contact = computed(() => websiteConfig.contact);
  const branding = computed(() => websiteConfig.branding);
  
  // Content
  const hero = computed(() => websiteConfig.hero);
  const sections = computed(() => websiteConfig.sections);
  const features = computed(() => websiteConfig.features);
  const navigation = computed(() => websiteConfig.navigation);
  const buttons = computed(() => websiteConfig.buttons);
  const newsletter = computed(() => websiteConfig.newsletter);
  const cart = computed(() => websiteConfig.cart);
  const badges = computed(() => websiteConfig.badges);
  const messages = computed(() => websiteConfig.messages);
  const images = computed(() => websiteConfig.images);
  
  // Settings
  const settings = computed(() => websiteConfig.settings);
  const seo = computed(() => websiteConfig.seo);
  const locale = computed(() => websiteConfig.locale);
  
  // Helper functions
  const formatPrice = (price) => {
    const { symbol, position } = websiteConfig.settings.currency;
    const formatted = parseFloat(price).toFixed(2);
    return position === 'before' ? `${symbol}${formatted}` : `${formatted}${symbol}`;
  };
  
  const getColor = (colorName) => {
    return websiteConfig.branding.colors[colorName] || '#000000';
  };
  
  const getImage = (imageName) => {
    return websiteConfig.images[imageName] || websiteConfig.images.placeholder;
  };
  
  const getMessage = (messageName) => {
    return websiteConfig.messages[messageName] || '';
  };
  
  const getButton = (buttonName) => {
    return websiteConfig.buttons[buttonName] || buttonName;
  };
  
  return {
    // Config objects
    business,
    contact,
    branding,
    hero,
    sections,
    features,
    navigation,
    buttons,
    newsletter,
    cart,
    badges,
    messages,
    images,
    settings,
    seo,
    locale,
    
    // Helper functions
    formatPrice,
    getColor,
    getImage,
    getMessage,
    getButton,
    
    // Direct access to config
    config: websiteConfig,
  };
}