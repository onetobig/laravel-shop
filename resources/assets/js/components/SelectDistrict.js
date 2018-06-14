// 加载数据
const addressData = require('china-area-data/v4/data');

// 引入 lodash
import _ from 'lodash';

Vue.component('select-district', {
    // 组件的属性
    props: {
        // 用来初始化省市区的值
        initValue: {
            type: Array,
            default: () => ([]),
        }
    },
    // 组件内的数据
    data() {
        return {
            provinces: addressData['86'], // 省列表
            cities: {}, // 城市列表
            districts: {}, // 地区列表
            provinceId: '', // 选中的省
            cityId: '',
            districtId: '',
        }
    },
    // 观察器
    watch: {
        // 选择的省发生改变时触发
        provinceId(newVal) {
            if (!newVal) {
                this.cities = {};
                this.cityId = '';
                return;
            }

            // 城市列表设置为当前省下的城市
            this.cities = addressData[newVal];
            // 如果当前选中的城市不在当前省下，则将选中的城市清空
            if (!this.cities[this.cityId]) {
                this.cityId = '';
            }
        },
        // 选择的区发生改变时触发
        cityId(newVal) {
            if (!newVal) {
                this.districts = {};
                this.districtId = '';
                return;
            }

            // 城市列表设置为当前城市的地区
            this.districts = addressData[newVal];
            // 如果当前选中的地区不在当城市下，则将选中的城市清空
            if (!this.districts[this.districtId]) {
                this.districtId = '';
            }
        },
        districtId() {
            this.$emit('change', [this.provinces[this.provinceId], this.cities[this.cityId], this.districts[this.districtId]])
        },
    },
    // 组件初始化
    created() {
        this.setFromValue(this.initValue);
    },
    methods: {
        setFromValue(value) {
            // 过滤空值
            value = _.filter(value);
            if(value.length === 0) {
                this.provinceId = '';
                return;
            }
            // 从当前省列表中找到与数组第一个元素同名的项的索引
            const provinceId = _.findKey(this.provinces, o => o === value[0]);
            // 没找到，清空省的值
            if (!provinceId) {
                this.provinceId = '';
                return;
            }
            // 找到，设置ID
            this.provinceId = provinceId;
            // 由于观察器的作用，这个时候城市列表已经变成了对应省的城市列表
            // 从当前城市列表找到与数组第二个元素同名的项的索引
            const cityId = _.findKey(addressData[provinceId], o => o === value[1]);
            // 没找到，清空城市的值
            if(!cityId) {
                this.cityId = '';
                return;
            }
            // 找到了，将当前城市设置成对应的ID
            this.cityId = cityId;
            // 由于观察器的作用，这个时候地区列表已经变成了对应城市的地区列表
            // 从当前地区列表找到与数组第三个元素同名的项的索引
            const districtId = _.findKey(addressData[cityId], o => o === value[2]);
            // 没找到，清空地区的值
            if(!districtId) {
                this.districtId = '';
                return;
            }
            // 找到了，将当前地区设置成对应的ID
            this.districtId = districtId;
        }
    }
});