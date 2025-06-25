<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import Button from '@/components/ui/button/Button.vue';
import { Dialog, DialogContent, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { format } from 'date-fns';
import { LoaderCircle, Pen, PenBoxIcon, Plus, TrashIcon } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import { toast } from 'vue-sonner';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Loan Wallet',
        href: '/loans',
    },
];

defineProps({
    loanWallets: Object,
    bankTypes: Object,
    loanTypes: Object,
});

const tableHeads = ['ID', 'Amount', 'Bank Type', 'Loan Type', 'Created At', 'Action'];

const form = useForm({
    amount: 0,
    bank_type_id: '',
    bank_type_others: '',
    loan_type_id: '',
});

const modalOpen = ref(false);

const editModalOpen = ref(false);

const selectedLoanWallet: any = ref(null);

function submit() {
    form.post(route('loans.store'), {
        onSuccess: (page) => {
            const { success, error }: any = page.props.flash;

            toast('Created!', {
                description: success,
                duration: 3000,
                position: 'bottom-center',
            });

            form.reset();

            modalOpen.value = false;
        },
    });
}

const deleteLoanWallet = (id: number) => {
    form.delete(route('loans.destroy', id), {
        onSuccess: (page) => {
            const { success, error }: any = page.props.flash;

            toast('Deleted!', {
                description: success,
                duration: 3000,
                position: 'bottom-center',
            });
        },
    });
};
const handleEdit = (loanWallet: any) => {
    form.amount = loanWallet.amount;
    form.bank_type_id = loanWallet.bank_type_id;
    form.loan_type_id = loanWallet.loan_type_id;
    selectedLoanWallet.value = loanWallet;
    form.errors = {};
    editModalOpen.value = true;
};

const handleUpdate = () => {
    form.patch(route('loans.update', selectedLoanWallet.value.id), {
        onSuccess: (page) => {
            const { success, error }: any = page.props.flash;

            editModalOpen.value = false;

            toast('Updated!', {
                description: success,
                duration: 3000,
                position: 'bottom-center',
            });

            form.reset();
        },
    });
};

const handleOpenModal = () => {
    form.reset();
    form.errors = {};
    modalOpen.value = true;
};

function resetIfOthers(fieldItem: string, fieldOthers: string) {
    watch(
        () => (form as any)[fieldItem],
        (newValue) => {
            if (newValue !== 'others') {
                (form as any)[fieldOthers] = '';
                (form as any).errors[fieldOthers] = '';
            }
        },
    );
}

resetIfOthers('bank_type_id', 'bank_type_others');
</script>

<template>
    <Head title="Loan Wallet" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-5">
            <div class="w-full">
                <Dialog v-model:open="modalOpen">
                    <Button class="float-end bg-blue-500 text-white hover:bg-blue-600" @click="handleOpenModal"><Plus /> Add Loan Wallet</Button>
                    <DialogContent class="sm:max-w-[425px]">
                        <DialogHeader>
                            <DialogTitle>Add Loan Wallet</DialogTitle>
                        </DialogHeader>
                        <form @submit.prevent="submit" class="space-y-4">
                            <div class="space-y-2">
                                <Label for="amount" class="text-right"> Amount </Label>
                                <Input
                                    type="number"
                                    id="amount"
                                    :class="form.errors.amount && 'border-red-500'"
                                    placeholder="Enter Amount"
                                    v-model="form.amount"
                                />
                                <InputError :message="form.errors.amount" />
                            </div>
                            <div class="space-y-2">
                                <Label for="loan_type" class="text-right"> Loan Type </Label>
                                <Select v-model="form.loan_type_id">
                                    <SelectTrigger class="w-full">
                                        <SelectValue placeholder="Select loan type" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem v-for="(loanType, index) in loanTypes" :key="index" :value="loanType.id">
                                            {{ loanType.name }}
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                                <InputError :message="form.errors.loan_type_id" />
                            </div>
                            <div class="space-y-2">
                                <Label for="bank_type" class="text-right"> Bank Type </Label>
                                <Select v-model="form.bank_type_id">
                                    <SelectTrigger class="w-full">
                                        <SelectValue placeholder="Select bank type" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem v-for="(bankType, index) in bankTypes" :key="index" :value="bankType.id">
                                            {{ bankType.name }}
                                        </SelectItem>
                                        <SelectItem value="others"> Others </SelectItem>
                                    </SelectContent>
                                </Select>
                                <InputError :message="form.errors.bank_type_id" />
                            </div>
                            <div class="space-y-2" v-if="form.bank_type_id === 'others'">
                                <Label for="bank_type" class="text-right"> Bank Type Others </Label>
                                <Input
                                    type="text"
                                    id="bank_type_others"
                                    :class="form.errors.bank_type_others && 'border-red-500'"
                                    placeholder="Enter Bank Type Others"
                                    v-model="form.bank_type_others"
                                />
                                <InputError :message="form.errors.bank_type_others" />
                            </div>
                            <DialogFooter>
                                <Button type="submit" :disabled="form.processing">
                                    <span v-if="form.processing" class="flex items-center gap-1"
                                        ><LoaderCircle class="h-4 w-4 animate-spin" /> Adding...
                                    </span>
                                    <span class="flex items-center gap-1" v-else><Plus /> Add</span>
                                </Button>
                            </DialogFooter>
                        </form>
                    </DialogContent>
                </Dialog>
            </div>
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead v-for="(item, id) in tableHeads" :key="id">{{ item }}</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="(loanWallet, index) in loanWallets" :key="index">
                        <TableCell> {{ loanWallet.id }}</TableCell>
                        <TableCell> {{ Number(loanWallet.amount).toLocaleString('en-PH', { style: 'currency', currency: 'PHP' }) }} </TableCell>
                        <TableCell> {{ loanWallet?.bank_type?.name }}</TableCell>
                        <TableCell class="capitalize"> {{ loanWallet?.loan_type?.name }}</TableCell>
                        <TableCell> {{ format(loanWallet.created_at, 'MMM dd, yyyy hh:mm a') }}</TableCell>
                        <TableCell class="flex gap-2">
                            <Button class="bg-blue-500 text-white hover:bg-blue-600" @click="handleEdit(loanWallet)"><PenBoxIcon /></Button>
                            <Button @click="deleteLoanWallet(loanWallet.id)" class="bg-red-500 text-white hover:bg-red-600"><TrashIcon /> </Button>
                        </TableCell>
                    </TableRow>
                    <TableRow v-if="loanWallets?.length === 0">
                        <TableCell colspan="6" class="text-center">No loan wallet found.</TableCell>
                    </TableRow>
                    <Dialog v-model:open="editModalOpen">
                        <DialogContent class="sm:max-w-[425px]">
                            <DialogHeader>
                                <DialogTitle
                                    >Editing {{ selectedLoanWallet?.amount }} ({{ selectedLoanWallet?.bank_type?.name }} -
                                    {{ selectedLoanWallet?.loan_type?.name }})...</DialogTitle
                                >
                            </DialogHeader>
                            <form @submit.prevent="handleUpdate" class="space-y-4">
                                <div class="space-y-2">
                                    <Label for="amount" class="text-right"> Amount </Label>
                                    <Input
                                        type="number"
                                        id="amount"
                                        :class="form.errors.amount && 'border-red-500'"
                                        placeholder="Enter Amount"
                                        v-model="form.amount"
                                    />
                                    <InputError :message="form.errors.amount" />
                                </div>
                                <div class="space-y-2">
                                    <Label for="loan_type" class="text-right"> Loan Type </Label>
                                    <Select v-model="form.loan_type_id">
                                        <SelectTrigger class="w-full">
                                            <SelectValue placeholder="Select loan type" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem v-for="(loanType, index) in loanTypes" :key="index" :value="loanType.id">
                                                {{ loanType.name }}
                                            </SelectItem>
                                        </SelectContent>
                                    </Select>
                                    <InputError :message="form.errors.loan_type_id" />
                                </div>
                                <div class="space-y-2">
                                    <Label for="bank_type" class="text-right"> Bank Type </Label>
                                    <Select v-model="form.bank_type_id">
                                        <SelectTrigger class="w-full">
                                            <SelectValue placeholder="Select bank type" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem v-for="(bankType, index) in bankTypes" :key="index" :value="bankType.id">
                                                {{ bankType.name }}
                                            </SelectItem>
                                            <SelectItem value="others"> Others </SelectItem>
                                        </SelectContent>
                                    </Select>
                                    <InputError :message="form.errors.bank_type_id" />
                                </div>
                                <div class="space-y-2" v-if="form.bank_type_id === 'others'">
                                    <Label for="bank_type" class="text-right"> Bank Type Others </Label>
                                    <Input
                                        type="text"
                                        id="bank_type_others"
                                        :class="form.errors.bank_type_others && 'border-red-500'"
                                        placeholder="Enter Bank Type Others"
                                        v-model="form.bank_type_others"
                                    />
                                    <InputError :message="form.errors.bank_type_others" />
                                </div>
                                <DialogFooter>
                                    <Button type="submit" :disabled="form.processing">
                                        <span v-if="form.processing" class="flex items-center gap-1"
                                            ><LoaderCircle class="h-4 w-4 animate-spin" /> Updating...
                                        </span>
                                        <span class="flex items-center gap-1" v-else><Pen /> Update</span>
                                    </Button>
                                </DialogFooter>
                            </form>
                        </DialogContent>
                    </Dialog>
                </TableBody>
            </Table>
        </div>
    </AppLayout>
</template>
