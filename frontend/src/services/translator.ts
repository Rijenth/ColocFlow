import { createI18n } from "vue-i18n";
import fr from "@/lang/fr.json";
import en from "@/lang/en.json";

// If a translation is missing, it will check in the fallback to get an alternative translation.
export const translator = createI18n({
  locale: "fr",
  fallbackLocale: "en",
  messages: {
    fr,
    en,
  },
});

export default translator;
