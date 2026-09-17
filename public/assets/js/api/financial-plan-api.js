export async function createFinancialPlan(data) {
    const response = await fetch('/api/plans', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    });

    const result = await response.json();

    if (!response.ok) {
        throw new Error(
            result.error ?? 'Unable to create financial plan.'
        );
    }

    return result;
}