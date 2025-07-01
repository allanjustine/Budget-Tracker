export function formattedAmount(amount: number | string) {
    return Number(amount)?.toLocaleString('en-US', { style: 'currency', currency: 'PHP' });
}
