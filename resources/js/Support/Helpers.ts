export function chunk(array: any[], size: number) {
    return array.reduce((chunks, item, index) => {
        if (index % size === 0) {
            chunks.push([item])
        } else {
            chunks[chunks.length - 1].push(item)
        }
        return chunks
    }, []);
}

export function mapRange (number: number, inMin: number, inMax: number, outMin: number, outMax: number) {
    return (number - inMin) * (outMax - outMin) / (inMax - inMin) + outMin;
}
