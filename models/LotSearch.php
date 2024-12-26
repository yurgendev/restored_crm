<?php
namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

class LotSearch extends Lot
{
    public $search;
    public $photoA_filter;
    public $photoD_filter;
    public $photoW_filter;
    public $photoL_filter;
    public $keys_filter;
    public $bos_filter;
    public $title_filter;

    public function rules()
    {
        return [
            [['id', 'account_id', 'auction_id', 'customer_id', 'warehouse_id', 'company_id', 'has_keys'], 'integer'],
            [['bos', 'photo_a', 'photo_d', 'photo_w', 'video', 'title', 'photo_l', 'status', 'status_changed', 'date_purchase', 'date_warehouse', 'payment_date', 'date_booking', 'date_container', 'date_unloaded', 'auto', 'vin', 'lot', 'url', 'line', 'booking_number', 'container', 'ata_data', 'search'], 'safe'],
            [['price'], 'number'],
            [['photoA_filter', 'photoD_filter', 'photoW_filter', 'photoL_filter', 'keys_filter', 'bos_filter', 'title_filter'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = Lot::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 2, // pagination 
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'status_changed' => $this->status_changed,
            'date_purchase' => $this->date_purchase,
            'date_warehouse' => $this->date_warehouse,
            'payment_date' => $this->payment_date,
            'date_booking' => $this->date_booking,
            'data_container' => $this->date_container,
            'date_unloaded' => $this->date_unloaded,
            'account_id' => $this->account_id,
            'auction_id' => $this->auction_id,
            'customer_id' => $this->customer_id,
            'warehouse_id' => $this->warehouse_id,
            'company_id' => $this->company_id,
            'price' => $this->price,
            'has_keys' => $this->has_keys,
            'ata_data' => $this->ata_data,
        ]);

        $query->andFilterWhere(['like', 'bos', $this->bos])
            ->andFilterWhere(['like', 'photo_a', $this->photo_a])
            ->andFilterWhere(['like', 'photo_d', $this->photo_d])
            ->andFilterWhere(['like', 'photo_w', $this->photo_w])
            ->andFilterWhere(['like', 'video', $this->video])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'photo_l', $this->photo_l])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'auto', $this->auto])
            ->andFilterWhere(['like', 'vin', $this->vin])
            ->andFilterWhere(['like', 'lot', $this->lot])
            ->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'line', $this->line])
            ->andFilterWhere(['like', 'booking_number', $this->booking_number])
            ->andFilterWhere(['like', 'container', $this->container]);

        if ($this->search) {
            $query->andFilterWhere(['or',
                ['like', 'vin', $this->search],
                ['like', 'auto', $this->search],
                ['like', 'lot', $this->search],
            ]);
        }

        $this->applyFilter($query, 'photo_a', $this->photoA_filter);
        $this->applyFilter($query, 'photo_d', $this->photoD_filter);
        $this->applyFilter($query, 'photo_w', $this->photoW_filter);
        $this->applyFilter($query, 'photo_l', $this->photoL_filter);
        $this->applyFilter($query, 'has_keys', $this->keys_filter);
        $this->applyFilter($query, 'bos', $this->bos_filter);
        $this->applyFilter($query, 'title', $this->title_filter);

        return $dataProvider;
    }

    private function applyFilter($query, $attribute, $filter)
    {
        if ($filter === 'Yes' || $filter === '1') {
            if ($attribute === 'has_keys') {
                $query->andWhere(['has_keys' => 1]);
            } else {
                $query->andWhere(['not', [$attribute => null]]);
                $query->andWhere(['<>', $attribute, '']);
            }
        } elseif ($filter === 'No' || $filter === '0') {
            if ($attribute === 'has_keys') {
                $query->andWhere(['has_keys' => 0]);
            } else {
                $query->andWhere(['or', [$attribute => null], [$attribute => '']]);
            }
        }
    }
}