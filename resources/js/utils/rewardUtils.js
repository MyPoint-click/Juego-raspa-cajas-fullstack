/**
 * Generates a random reward code with specified format
 * @param {number} length Length of the code
 * @returns {string} Generated code
 */
export const generateCode = (length = 8) => {
    const characters = "ABCDEFGHJKLMNPQRSTUVWXYZ23456789"; // Excluding similar looking characters
    let result = "";

    // Generate random characters
    for (let i = 0; i < length; i++) {
        const randomIndex = Math.floor(Math.random() * characters.length);
        result += characters.charAt(randomIndex);
    }

    // Format code with dashes for better readability (e.g., ABCD-1234)
    if (length >= 8) {
        const middle = Math.floor(length / 2);
        result = result.substring(0, middle) + "-" + result.substring(middle);
    }

    return result;
};

/**
 * Validates if a code is in the correct format
 * @param {string} code Code to validate
 * @returns {boolean} Whether the code is valid
 */
export const validateCode = (code) => {
    // Basic validation - can be expanded based on business requirements
    if (!code) return false;

    // Remove any dashes for validation
    const cleanCode = code.replace(/-/g, "");

    // Check if code has appropriate length
    return cleanCode.length >= 6 && cleanCode.length <= 12;
};
