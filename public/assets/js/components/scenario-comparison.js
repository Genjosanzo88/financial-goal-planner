class ScenarioComparison extends HTMLElement {
    connectedCallback() {
        document.addEventListener(
            'financial-plan-created',
            (event) => {
                this.render(event.detail);
            }
        );
    }

    render(plan) {
        const formatCurrency = (amount) => {
            return new Intl.NumberFormat('es-ES', {
                style: 'currency',
                currency: 'EUR'
            }).format(amount);
        };

        const difference =
            plan.finalCapital - plan.targetAmount;

        this.innerHTML = `
            <section class="card results">
                <div class="results-header">
                    <div>
                        <p class="eyebrow">Resultado</p>
                        <h2>${plan.goalName}</h2>
                        <p>${plan.clientName}</p>
                    </div>

                    <span class="status">
                        ${
                            plan.targetReached
                                ? 'Objetivo alcanzado'
                                : 'Objetivo no alcanzado'
                        }
                    </span>
                </div>

                <div class="result-grid">
                    <article>
                        <span>Objetivo</span>
                        <strong>
                            ${formatCurrency(plan.targetAmount)}
                        </strong>
                    </article>

                    <article>
                        <span>Capital proyectado</span>
                        <strong>
                            ${formatCurrency(plan.finalCapital)}
                        </strong>
                    </article>

                    <article>
                        <span>Total aportado</span>
                        <strong>
                            ${formatCurrency(plan.totalContributed)}
                        </strong>
                    </article>

                    <article>
                        <span>Rentabilidad estimada</span>
                        <strong>
                            ${formatCurrency(plan.estimatedReturn)}
                        </strong>
                    </article>

                    <article>
                        <span>Aportación actual</span>
                        <strong>
                            ${formatCurrency(plan.monthlyContribution)}
                        </strong>
                    </article>

                    <article>
                        <span>Aportación necesaria</span>
                        <strong>
                            ${formatCurrency(
                                plan.requiredMonthlyContribution
                            )}
                        </strong>
                    </article>
                </div>

                <div class="difference">
                    ${
                        difference >= 0
                            ? `Superarías el objetivo en ${formatCurrency(difference)}.`
                            : `Te faltarían ${formatCurrency(Math.abs(difference))}.`
                    }
                </div>
            </section>
        `;
    }
}

customElements.define(
    'scenario-comparison',
    ScenarioComparison
);