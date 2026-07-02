export function truncate(text, maxLength) {
    if (!text) {
        return '';
    }

    if (text.length <= maxLength) {
        return text;
    }

    const slicedText = text.slice(0, maxLength).trim();
    const lastSpaceIndex = slicedText.lastIndexOf(' ');

    if (lastSpaceIndex === -1) {
        return `${slicedText}…`;
    }

    return `${slicedText.slice(0, lastSpaceIndex)}…`;
}