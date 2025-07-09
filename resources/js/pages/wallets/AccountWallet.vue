<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import {
    AlertDialog,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
} from '@/components/ui/alert-dialog';
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
import { CheckCircle, LoaderCircle, Pen, PenBoxIcon, Plus, TrashIcon } from 'lucide-vue-next';
import { h, ref, watch } from 'vue';
import { toast } from 'vue-sonner';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Account Wallet',
        href: '/account-wallets',
    },
];

const { accountWallets } = defineProps({
    accountWallets: Object,
    bankTypes: Object,
});

const tableHeads = ['ID', 'Amount', 'Bank Type', 'Created At', 'Action'];

const form = useForm({
    amount: 0,
    bank_type_id: '',
    bank_type_others: '',
});

const modalOpen = ref(false);

const editModalOpen = ref(false);

const selectedAccountWallet: any = ref(null);

const deleteAlertDialogOpen: any = ref(false);

const selectedToDelete: any = ref(null);

function submit() {
    form.post(route('account-wallets.store'), {
        onSuccess: (page) => {
            const { success, error }: any = page.props.flash;

            toast('Created!', {
                description: success,
                duration: 3000,
                position: 'bottom-center',
                icon: h(CheckCircle, { class: 'text-green-500 w-5 h-5' }),
            });

            form.reset();

            modalOpen.value = false;
        },
    });
}

const deleteAccountWallet = (id: number) => {
    form.delete(route('account-wallets.destroy', id), {
        onSuccess: (page) => {
            const { success, error }: any = page.props.flash;

            toast('Deleted!', {
                description: success,
                duration: 3000,
                position: 'bottom-center',
                icon: h(CheckCircle, { class: 'text-green-500 w-5 h-5' }),
            });

            deleteAlertDialogOpen.value = false;
        },
    });
};
const handleEdit = (accountWallet: any) => {
    form.amount = accountWallet.amount;
    form.bank_type_id = accountWallet.bank_type_id;
    selectedAccountWallet.value = accountWallet;
    form.errors = {};
    editModalOpen.value = true;
};

const handleUpdate = () => {
    form.patch(route('account-wallets.update', selectedAccountWallet.value.id), {
        onSuccess: (page) => {
            const { success, error }: any = page.props.flash;

            editModalOpen.value = false;

            toast('Updated!', {
                description: success,
                duration: 3000,
                position: 'bottom-center',
                icon: h(CheckCircle, { class: 'text-green-500 w-5 h-5' }),
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

watch(
    () => form.bank_type_id,
    (newValue) => {
        if (newValue !== 'others') {
            form.bank_type_others = '';
            form.errors.bank_type_others = '';
        }
    },
);

const handleAlertDialogOpen = (item: number) => {
    selectedToDelete.value = item;
    deleteAlertDialogOpen.value = true;
};
</script>

<template>
    <Head title="Account Wallet" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-5">
            <div class="w-full">
                <Dialog v-model:open="modalOpen">
                    <Button class="float-end bg-blue-500 text-white hover:bg-blue-600" @click="handleOpenModal"><Plus /> Add Account Wallet</Button>
                    <DialogContent class="sm:max-w-[425px]">
                        <DialogHeader>
                            <DialogTitle>Add Account Wallet</DialogTitle>
                        </DialogHeader>
                        <form @submit.prevent="submit" class="space-y-4">
                            <div class="space-y-2">
                                <Label for="amount" class="text-right"> Amount </Label>
                                <Input
                                    type="number"
                                    id="amount"
                                    :class="form.errors.amount && 'border-red-500'"
                                    placeholder="Enter Amount"
                                    step="any"
                                    v-model="form.amount"
                                />
                                <InputError :message="form.errors.amount" />
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
                    <TableRow v-for="(accountWallet, index) in accountWallets" :key="index">
                        <TableCell> {{ accountWallet.id }}</TableCell>
                        <TableCell> {{ Number(accountWallet.amount).toLocaleString('en-PH', { style: 'currency', currency: 'PHP' }) }} </TableCell>
                        <TableCell> {{ accountWallet?.bank_type?.name }}</TableCell>
                        <TableCell> {{ format(accountWallet.created_at, 'MMM dd, yyyy hh:mm a') }}</TableCell>
                        <TableCell class="flex gap-2">
                            <Button class="bg-blue-500 text-white hover:bg-blue-600" @click="handleEdit(accountWallet)"><PenBoxIcon /></Button>
                            <Button @click="handleAlertDialogOpen(accountWallet)" class="bg-red-500 text-white hover:bg-red-600"
                                ><TrashIcon />
                            </Button>
                        </TableCell>
                    </TableRow>
                    <TableRow v-if="accountWallets?.length === 0">
                        <TableCell colspan="5" class="text-center">No account wallet found.</TableCell>
                    </TableRow>
                    <Dialog v-model:open="editModalOpen">
                        <DialogContent class="sm:max-w-[425px]">
                            <DialogHeader>
                                <DialogTitle
                                    >Editing {{ selectedAccountWallet?.amount }} ({{ selectedAccountWallet?.bank_type?.name }})...</DialogTitle
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
                                        step="any"
                                    />
                                    <InputError :message="form.errors.amount" />
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
        <AlertDialog v-model:open="deleteAlertDialogOpen">
            <AlertDialogContent>
                <AlertDialogHeader>
                    <AlertDialogTitle
                        >Are you absolutely sure you want to delete amount
                        {{ Number(selectedToDelete?.amount).toLocaleString('en-PH', { style: 'currency', currency: 'PHP' }) }} with bank type of
                        {{ selectedToDelete?.bank_type?.name }}?</AlertDialogTitle
                    >
                    <AlertDialogDescription>
                        This action cannot be undone. This will permanently remove your data from our servers.
                    </AlertDialogDescription>
                </AlertDialogHeader>
                <AlertDialogFooter>
                    <AlertDialogCancel>Cancel</AlertDialogCancel>
                    <Button
                        @click="deleteAccountWallet(selectedToDelete.id)"
                        type="button"
                        :disabled="form.processing"
                        class="bg-red-500 text-white hover:bg-red-600"
                        ><span v-if="form.processing" class="flex items-center gap-1"><LoaderCircle class="animate-spin" /> Deleting...</span>
                        <span v-else>Yes, Delete</span></Button
                    >
                </AlertDialogFooter>
            </AlertDialogContent>
        </AlertDialog>
    </AppLayout>
</template>
