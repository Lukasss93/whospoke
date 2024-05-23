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
