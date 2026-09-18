import {
    getFinancialPlans
} from '../api/financial-plan-api.js';

class RecentPlans extends HTMLElement {
    connectedCallback() {
        this.load();

        document.addEventListener(
            'financial-plan-created',
            () => this.load()
        );
    }

    formatCurrency(amount) {
        return new Intl.NumberFormat('es-ES', {
            style: 'currency',
            currency: 'EUR'
        }).format(amount);
    }

    escapeHtml(value) {
        return String(value).replace(
            /[&<>"']/g,
            (character) => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            })[character]
        );
    }

    async load() {
        try {
            const plans = await getFinancialPlans();

            this.render(plans);
        } catch {
            this.innerHTML = '';
        }
    }

    render(plans) {
        if (plans.length === 0) {
            this.innerHTML = '';
            return;
        }

        const items = plans
            .map((plan) => `
                <article class="recent-plan">
                    <div>
                        <strong>
                            ${this.escapeHtml(plan.goalName)}
                        </strong>

                        <span>
                            ${this.escapeHtml(plan.clientName)}
                        </span>
                    </div>

                    <div>
                        <strong>
                            ${this.formatCurrency(
                                plan.targetAmount
                            )}
                        </strong>

                        <span>
                            ${plan.years} años
                        </span>
                    </div>
                </article>
            `)
            .join('');

        this.innerHTML = `
            <section class="card">
                <p class="eyebrow">
                    Historial
                </p>

                <h2>
                    Planes recientes
                </h2>

                <div class="recent-plans">
                    ${items}
                </div>
            </section>
        `;
    }
}

customElements.define(
    'recent-plans',
    RecentPlans
);