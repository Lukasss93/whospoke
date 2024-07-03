import {DateTime} from "luxon";

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

export function mapRange(number: number, inMin: number, inMax: number, outMin: number, outMax: number) {
    return (number - inMin) * (outMax - outMin) / (inMax - inMin) + outMin;
}

export function calcTime(started: Date | null, ended: Date | null): { minutes: string, seconds: string } {
    if (started === null) {
        return {minutes: '00', seconds: '00'};
    }

    if (ended === null) {
        ended = new Date();
    }

    const startedL = DateTime.fromJSDate(started);
    const endedL = DateTime.fromJSDate(ended);

    const diff = endedL.diff(startedL, ['minutes', 'seconds']).toObject();

    return {
        minutes: Math.trunc(diff.minutes ?? 0).toString().padStart(2, '0'),
        seconds: Math.trunc(diff.seconds ?? 0).toString().padStart(2, '0'),
    };
}
